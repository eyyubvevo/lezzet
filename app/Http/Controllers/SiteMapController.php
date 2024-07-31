<?php

namespace App\Http\Controllers;

use App\Models\Product;
use View;
use Response;
use App\Models\Category;
use App\Models\News;


class SiteMapController extends Controller
{
  
  public function index(){
      
      $products = Product::get();
      $categories = Category::get();
      $news = News::get();
      
      
     
     $content = View::make('sitemap', ['products' => $products,'categories' => $categories,'news' => $news]);
    return Response::make($content)->header('Content-Type', 'text/xml;charset=utf-8');
  }
}
