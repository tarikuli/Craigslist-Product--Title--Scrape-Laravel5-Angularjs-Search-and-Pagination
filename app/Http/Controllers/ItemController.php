<?php
// searchDB()
// ng-app
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Item;

class ItemController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
//         $input = $request->all();

        if ($request->get('search')) {
            // Search Result Return
            $items = Item::where("title", "LIKE", "%{$request->get('search')}%")->paginate(120);
        } else {
            // General List page Result Return.
            $items = Item::paginate(120);
        }
        
        return response($items);
    }



    /**
     * Scrape first 1000 items’ titles and prices starting from craigslist
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        // Step1: Truncate exist data from item Table.
        DB::table('items')->truncate();
        
        $itemsList = [];
        // Step2: Craigslist Show 120 items in a page so loop for 1000 items 
        for ($i = 0; $i <= 1000; $i += 120) {
            // Step3: Curl & Parse Items titel and value using PHP  DOMDocument class and Curl Function
            $html = $this->curlData($i);
            $dom = new \DOMDocument();
            @$dom->loadHTML($html);
            $finder = new \DomXPath($dom);
            $elements = $finder->query("//*[contains(@class, 'result-info')]");
            
            foreach ($elements as $element) {
                $titelName = $element->getElementsByTagName("a");
                $valueDate = $titelName->item(0)->nodeValue;
                
                $price = $element->getElementsByTagName("span");
                $proPrice = $price->item(3)->nodeValue;
                
                $pos = strpos($proPrice, "$");
                
                if ($pos === false) {
                    $proPrice = 0;
                }
                // Step4: Push Items and price value in Array for insert Items table. 
                $itemsList[]=['title' => $titelName->item(0)->nodeValue, 'price' => $proPrice];
                
            }
            set_time_limit(0);
        }
        // Step 5: Get first 1000 element from array.
        $itemsList = array_slice($itemsList, 0, 1000); 
        
        DB::table('items')->insert($itemsList);
        #Log::info('Deduge Array : ' . print_r(($itemsList), true));

        // Step 6:After insert return to Item List.
        return Item::where('id',0)->delete();
    }

    private function curlData($index)
    {
        // INIT CURL
        $ch = curl_init();
        
        // SET URL FOR THE POST FORM LOGIN
        curl_setopt($ch, CURLOPT_URL, 'https://accounts.craigslist.org/');
        
        // ENABLE HTTP POST
        $email = "tarikuli@yahoo.com";
        $pass = "New@York#2018";
        $url = "inputEmailHandle=" . urlencode($email) . "&inputPassword=" . urlencode($pass);
        
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $url);
        
        $agent = "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.4) Gecko/20030624 Netscape/7.1 (ax)";
        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
        
        ob_start();
        curl_exec($ch);
        $err = curl_error($ch);
        ob_end_clean(); // execute the curl command
        
        curl_close($ch);
        unset($ch);
        
        $ch = curl_init();
        // second curl
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies.txt");
        curl_setopt($ch, CURLOPT_URL, 'https://newyork.craigslist.org/search/bka?s='.$index);
        $content = curl_exec($ch);
        
        curl_close($ch);
        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return ($content);
            // print_r(json_decode($response));
        }
    }
}
