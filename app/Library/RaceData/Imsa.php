<?php


namespace App\Library\RaceData;


use App\Models\Car;
use App\Models\CarClass;
use App\Models\Race;
use App\Models\Result;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PHPHtmlParser\Dom;
use Spatie\SimpleExcel\SimpleExcelReader;

class Imsa
{
    private static $topUrl = "http://results.imsa.com/top.php";

    public static function getSeasons(): Collection
    {
        return self::getData('season');
    }

    public static function getRaces(): Collection
    {
        return self::getData('event');
    }

    public static function getLatestSeason()
    {
        return self::getSeasons()->last();
    }

    public static function getData(string $name): Collection
    {
        $response = Http::get(self::$topUrl);
        $dom = new Dom();
        $dom->loadStr($response->body());
        $seasonOptions = $dom->find("[name=$name]");
        $options = collect($seasonOptions[0]->getChildren())->filter(function ($node) {
            return ($node instanceof \PHPHtmlParser\Dom\Node\HtmlNode);
        });

        return $options->map(function ($option) {
            return $option->tag->getAttribute('value')->getValue();
        });
    }

    public static function getResultsCSV(Response $response)
    {
        $dom = new Dom();
        $dom->loadStr($response->body());
        $links = $dom->find("a[href*='Result']");
        $csvLinks = collect($links)->filter(function ($link) {
            $value = $link->tag->getAttribute('href')->getValue();
            return (Str::containsAll($value, ['Results', 'Official', 'WeatherTech', '.CSV']) && (!(Str::contains($value, ['Qualifying']))));
        });

        return $csvLinks->map(function ($csvLink) {
            return $csvLink->tag->getAttribute('href')->getValue();
        });
    }

    public static function test()
    {
//        $testLinks = collect([
//            "http://results.imsa.com/Results/21_2021/02_Daytona%20International%20Speedway/01_IMSA%20WeatherTech%20SportsCar%20Championship/202101301540_Race/24_Hour%2024/03_Results_Race_Official.CSV",
//            "http://results.imsa.com/Results/21_2021/05_Sebring%20International%20Raceway/01_IMSA%20WeatherTech%20SportsCar%20Championships/202103201010_Race/12_Hour%2012/03_Results_Race_Official.CSV",
//            "http://results.imsa.com/Results/21_2021/08_Mid-Ohio%20Sports%20Car%20Course/01_IMSA%20WeatherTech%20SportsCar%20Championships/202105161440_Race/03_Results_Race_Official.CSV"
//        ]);
        $links = self::getRaceCSVLinks();
        $latestSeason = self::getLatestSeason();
        $races = Race::with('track')->get();
        $races->map(function ($race) use ($links, $latestSeason) {
            $matchedLink = $links->filter(function ($link) use ($race) {
                //TODO add more logic to not get the wrong file here
                return Str::contains(urldecode($link), $race->track->name);
            })->first();

            if ($matchedLink) {
                $filePath = "imsa/{$latestSeason}-{$race->track->name}.csv";
                self::downloadAndSave($matchedLink, $filePath);
                self::parseResults($race, $filePath);
            }
        });
    }

    public static function getRaceCSVLinks()
    {
        $races = self::getRaces();
        $latestSeason = self::getLatestSeason();

        $results = collect();

        $races->map(function ($race) use ($latestSeason, $results) {
            $encodedRace = rawurlencode($race);
            $response = Http::get("http://results.imsa.com/tree.php?season=$latestSeason&event=$encodedRace");
            $results->push(self::getResultsCSV($response));
        });

        return collect($results)->filter()->flatten()->map(function ($csvLink) {
            return "http://results.imsa.com/$csvLink";
        });
    }

    public static function downloadAndSave(string $link, string $path)
    {
        if (!(Storage::exists($path))) {
            Storage::put($path, file_get_contents($link));
        }
    }

    public static function parseResults(Race $race, string $path)
    {
        if (!Storage::exists($path)) {
            return null;
        } else {
            $fullPath = Storage::path($path);
        }

        //Create class if it does not exists
        $classes = SimpleExcelReader::create($fullPath, 'csv')->useDelimiter(';')->getRows();
        $classes->unique('CLASS')->each(function ($row) {
            CarClass::firstOrCreate([
                'name' => $row['CLASS']
            ]);
        });

        //Create cars
        $cars = SimpleExcelReader::create($fullPath, 'csv')->useDelimiter(';')->getRows();
        $cars->each(function ($row) {
            $car = Car::where('series_id', 1)
                ->where('number', $row['NUMBER'])
                ->where('car_class_id', CarClass::where('name', '=', $row['CLASS'])->first()->id ?? 0)
                ->first();

            if (!$car) {
                Car::create([
                    'series_id' => 1,
                    'number' => $row['NUMBER'],
                    'car_class_id' => CarClass::where('name', '=', $row['CLASS'])->first()->id ?? 0,
                    'image' => 'todo'
                ]);
            }
        });

        //Create results
        //Get all the classes
        $rows = SimpleExcelReader::create($fullPath, 'csv')->useDelimiter(';')->getRows();
        // Loop over the unique classes
        $rows->unique('CLASS')->each(function ($row) use ($race, $fullPath) {
            $results = SimpleExcelReader::create($fullPath, 'csv')->useDelimiter(';')->getRows();
            $index = 0;
            //Get all the cars for just that class and loop over them
            $results->where('CLASS', '=', $row['CLASS'])->each(function ($row) use ($race, &$index) {
                Result::firstOrCreate([
                    'end_position' => (int)($index + 1),
                    'race_id' => $race->id,
                    'car_id' => Car::where('number', '=', $row['NUMBER'])->first()->id ?? 0
                ]);
                $index++;
            });
        });
    }
}
