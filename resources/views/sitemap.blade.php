<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>

<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<!-- created with Free Online Sitemap Generator www.xml-sitemaps.com -->


        @foreach(['az','en','ru'] as $locale)

    <url>
        <loc>{{ URL::route("{$locale}.home" ) }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(Carbon\Carbon::now())) }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>



    <url>
        <loc>{{ URL::route("{$locale}.partners" ) }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(Carbon\Carbon::now()))}}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

  <url>
        <loc>{{ URL::route("{$locale}.news" ) }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(Carbon\Carbon::now())) }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>


  <url>
        <loc>{{ URL::route("{$locale}.general_questions" ) }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(Carbon\Carbon::now())) }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

  <url>
        <loc>{{ URL::route("{$locale}.contact" ) }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(Carbon\Carbon::now())) }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

 <url>
        <loc>{{ URL::route("{$locale}.shoppingCart" ) }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(Carbon\Carbon::now())) }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

@endforeach








@foreach(['az','en','ru'] as $locale)
@foreach($categories as $category)
    <url>
        <loc>{{ URL::route("{$locale}.home.products",$category->getTranslation('slug',$locale) ) }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(Carbon\Carbon::now()))  }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
@endforeach


@foreach($products as $product)
    <url>
        <loc>{{ URL::route("{$locale}.home.product.show",['categorySlug' => $product->category->getTranslation('slug',$locale),'slug' => $product->getTranslation('slug',$locale)] ) }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(Carbon\Carbon::now()))  }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
@endforeach

@foreach($news as $item)
    <url>
        <loc>{{ URL::route("{$locale}.home.products",$item->getTranslation('slug',$locale) ) }}</loc>
        <lastmod>{{ gmdate(DateTime::W3C, strtotime(Carbon\Carbon::now()))  }}</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
@endforeach



@endforeach
</urlset>