<h1>Craigslist-Product-Title-Scrape-Laravel5-Angularjs-Search-and-Pagination</h1>
<br>
URL: http://35.175.221.208/#/items
<br>
<h1>Goal of this App</h1>
<br>
-Build a crawler that scrapes first 1000 items’ titles and prices starting from http://newyork.craigslist.org/search/bka
<br>
-Build a DB that stores the results
<br>
-Build a text search engine with a web interface that allows you to input text, search, and see results with pagination
<br>
-Deploy above search engine on AWS using services of your choice. If you have time left, making the architecture scalable is a plus.
<br>
-Check-in the codes to Git on Github periodically.
<br>

<h1>Main business logic implemented here</h1><br>
https://github.com/tarikuli/Craigslist-Product-Title-Scrape-Laravel5-Angularjs-Search-and-Pagination/blob/master/app/Http/Controllers/ItemController.php
<br>
<h2>index function used for Search and List page view with pagenation</h2>
<pre> 
    public function index(Request $request)
    {
        if ($request->get('search')) {
            // Search Result Return
            $items = Item::where("title", "LIKE", "%{$request->get('search')}%")->paginate(120);
        } else {
            // General List page Result Return.
            $items = Item::paginate(120);
        }
        
        return response($items);
    }
</pre>
<br>

<h1>destroy function used for<h1> 
-- Step 1: Truncate exist data from item Table.<br>
-- Step 2: Craigslist Show 120 items in a page so loop for 1000 items<br> 
-- Step 3: Curl & Parse Items titel and value using PHP  DOMDocument class and Curl Function<br>
-- Step 4: Push Items and price value in Array for insert Items table.<br>
-- Step 5: Get first 1000 element from array.<br>
-- Step 6:After insert return to Item List.<br>
<pre>
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
<pre>

## AWS architecture

