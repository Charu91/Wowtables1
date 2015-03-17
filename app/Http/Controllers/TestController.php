<?php namespace WowTables\Http\Controllers;

use WowTables\Http\Requests;
use WowTables\Http\Controllers\Controller;

use Illuminate\Http\Request;
use WowTables\Http\Models\Schedules;
use Illuminate\Contracts\Filesystem\Cloud;
use WowTables\Http\Models\RestaurantLocation;
use Symfony\Component\DomCrawler\Crawler;

class TestController extends Controller {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Schedules $schedules, RestaurantLocation $restaurantLocation)
	{
        //$restaurantLocation->fetch(21, []);

        /*
        $fetchSchedules = $schedules->fetchAll();

        if($fetchSchedules['status'] === 'success'){
            return view('test', ['schedules' => $fetchSchedules['schedules']]);
        }else{
            return response('Something went wrong', 400);
        }
        */

        $html = <<<'HTML'
<h1 id="main-menu-title">Main Menu Title</h1>
<p><strong>The Main Title Description</strong></p>
<h2 id="appetizers">Appetizers</h2>
<p><em>The Appetizer Description</em></p>
<h4 id="item-1">Item 1</h4>
<p><strong>veg</strong>,<strong>non-veg</strong>,<strong>specials</strong></p>
<p><em>Item 1 Description</em></p>
<h4 id="item-2">Item 2</h4>
<p><em>Item 2 Description</em></p>
<h4 id="item-3">Item 3</h4>
<p><em>Item 3 Description</em></p>
<h4 id="item-4">Item 4</h4>
<p><em>Item 4 Description</em></p>
<h2 id="main-course">Main Course</h2>
<p><em>The Main Course Description</em></p>
<h3 id="submenu-1">SubMenu 1</h3>
<p><em>Sub Menu Description</em></p>
<h4 id="item-1">Item 1</h4>
<p><em>Item 1 Description</em></p>
<h4 id="item-2">Item 2</h4>
<p><em>Item 2 Description</em></p>
<h4 id="item-3">Item 3</h4>
<p><em>Item 3 Description</em></p>
<h4 id="item-4">Item 4</h4>
<p><em>Item 4 Description</em></p>
<h3 id="submenu-2">SubMenu 2</h3>
<p><em>Sub Menu Description</em></p>
<h4 id="item-1">Item 1</h4>
<p><em>Item 1 Description</em></p>
<h4 id="item-2">Item 2</h4>
<p><em>Item 2 Description</em></p>
<h4 id="item-3">Item 3</h4>
<p><em>Item 3 Description</em></p>
<h4 id="item-4">Item 4</h4>
<p><em>Item 4 Description</em></p>
<h3 id="submenu-3">SubMenu 3</h3>
<p><em>Sub Menu Description</em></p>
<h4 id="item-1">Item 1</h4>
<p><em>Item 1 Description</em></p>
<h4 id="item-2">Item 2</h4>
<p><em>Item 2 Description</em></p>
<h4 id="item-3">Item 3</h4>
<p><em>Item 3 Description</em></p>
<h4 id="item-4">Item 4</h4>
<p><em>Item 4 Description</em></p>
<h2 id="dessert">Dessert</h2>
<p><em>Dessert Description</em></p>
<h4 id="item-1">Item 1</h4>
<p><em>Item 1 Description</em></p>
<h4 id="item-2">Item 2</h4>
<p><em>Item 2 Description</em></p>
<h4 id="item-3">Item 3</h4>
<p><em>Item 3 Description</em></p>
<h4 id="item-4">Item 4</h4>
<p><em>Item 4 Description</em></p>
HTML;


        $crawler = new Crawler($html);

        $menu = [];

        $menu['title'] = $crawler->filter('h1')->text();
        if( $crawler->filter('h1 + p > em')->count()){
            $menu['description'] = $crawler->filter('h1 + p > em')->text();
        }

        $n = 0; $o = 0; $p = 0;
        $current_item = '';

        $crawler->filter('h1')->siblings()->each(function(Crawler $node, $i) use (&$menu, &$n, &$o, &$p, &$current_item){

            if($node->nodeName() === 'h2'){
                if(!isset($menu['menu'])) $menu['menu'] = [];
                ++$n;
                $menu['menu'][$n] = [];
                $menu['menu'][$n]['heading'] = $node->text();


                $current_item = 'menu-heading';
            }

            if($node->nodeName() === 'h3'){

                ++$o;
                $menu['menu'][$n]['sub-menu'][$o] = [];
                $menu['menu'][$n]['sub-menu'][$o]['heading'] = $node->text();

                $current_item = 'submenu-heading';
            }

            if($node->nodeName() === 'h4'){
                ++$p;

                if($current_item === 'menu-heading' || $current_item === 'menu-heading-item'){
                    if(!isset($menu['menu'][$n]['items'])) $menu['menu'][$n]['items'] = [];
                    if(!isset($menu['menu'][$n]['items'][$p])) $menu['menu'][$n]['items'][$p] = [];
                    $menu['menu'][$n]['items'][$p]['title'] = $node->text();
                    $current_item = 'menu-heading-item';
                }else if($current_item === 'submenu-heading' || $current_item === 'submenu-heading-item') {
                    if(!isset($menu['menu'][$n]['sub-menu'][$o]['items'])) $menu['menu'][$n]['sub-menu'][$o]['items'] = [];
                    if(!isset($menu['menu'][$n]['sub-menu'][$o]['items'][$p])) $menu['menu'][$n]['sub-menu'][$o]['items'][$p] = [];
                    $menu['menu'][$n]['sub-menu'][$o]['items'][$p]['title'] = $node->text();
                    $current_item = 'submenu-heading-item';
                }


            }

            if($node->nodeName() === 'p' && $node->children()->eq(0)->nodeName() === 'em'){
                if(isset($menu['menu'])){
                    if($current_item === 'menu-heading'){
                        $menu['menu'][$n]['description'] = $node->text();
                    }else if($current_item === 'submenu-heading'){
                        $menu['menu'][$n]['sub-menu'][$o]['description'] = $node->text();
                    }else if($current_item === 'menu-heading-item'){
                        $menu['menu'][$n]['items'][$p]['description'] = $node->text();
                    }else if($current_item === 'submenu-heading-item'){
                        $menu['menu'][$n]['sub-menu'][$o]['items'][$p]['description'] = $node->text();
                    }
                }
            }

            if($node->nodeName() === 'p' && $node->children()->eq(0)->nodeName() === 'strong'){
                if(isset($menu['menu'])){
                    if($current_item === 'menu-heading-item'){
                        $node->children()->filter('strong')->each(function(Crawler $node, $i) use (&$menu, &$n, &$o, &$p){
                            if(!isset($menu['menu'][$n]['items'][$p]['tags'])) $menu['menu'][$n]['items'][$p]['tags'] = [];
                            $menu['menu'][$n]['items'][$p]['tags'][] = $node->text();
                        });
                    }else if($current_item === 'submenu-heading-item'){
                        $node->children()->filter('strong')->each(function(Crawler $node, $i) use (&$menu, &$n, &$o, &$p){
                            if(!isset($menu['menu'][$n]['sub-menu'][$o]['items'][$p]['tags'])) $menu['menu'][$n]['sub-menu'][$o]['items'][$p]['tags'] = [];
                            $menu['menu'][$n]['sub-menu'][$o]['items'][$p]['tags'][] = $node->text();
                        });
                        //$menu['menu'][$n]['sub-menu'][$o]['items'][$p]['description'] = $node->text();
                    }
                }
            }
        });

        $newMenu = [];

        $newMenu['title'] = $menu['title'];
        if(isset($newMenu['description'])){
            $newMenu['description'] = $menu['description'];
        }

        $newMenu['menu'] = [];

        foreach($menu['menu'] as $mm){
            $current_menu = [];

            $current_menu['heading'] = $mm['heading'];
            if(isset($mm['description'])){
                $current_menu['description'] = $mm['description'];
            }

            if(isset($mm['items'])){
                foreach($mm['items'] as $item){
                    $itemArray = [];

                    $itemArray['title'] = $item['title'];
                    if(isset($item['tags'])) $itemArray['tags'] = $item['tags'];
                    if(isset($item['description'])) $itemArray['description'] = $item['description'];

                    if(!isset($current_menu['items'])){
                        $current_menu['items'] = [];
                    }

                    $current_menu['items'][] = $itemArray;
                }
            }else if(isset($mm['sub-menu'])){

                foreach($mm['sub-menu'] as $mmm){
                    $submenu = [];
                    if(!isset($current_menu['sub-menu'])) $current_menu['sub-menu'] = [];

                    $submenu['heading'] = $mmm['heading'];
                    if(isset($mmm['description'])) $submenu['description'] = $mmm['description'];

                    foreach($mmm['items'] as $item){
                        $itemArray = [];

                        $itemArray['title'] = $item['title'];
                        if(isset($item['tags'])) $itemArray['tags'] = $item['tags'];
                        if(isset($item['description'])) $itemArray['description'] = $item['description'];

                        if(!isset($submenu['items'])){
                            $submenu['items'] = [];
                        }

                        $submenu['items'][] = $itemArray;
                    }

                    $current_menu['sub-menu'][] = $submenu;
                }


            }

            $newMenu['menu'][] = $current_menu;
        }

        return response()->json($newMenu);

	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
