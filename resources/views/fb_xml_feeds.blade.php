<rss xmlns:g="http://base.google.com/ns/1.0" xmlns:c="http://base.google.com/cns/1.0" version="2.0">
    <channel>
    <title>
    <![CDATA[ RegalFurniture ]]>
    </title>
    <link><![CDATA[ https://regalfurniturebd.com ]]></link>
    <description><![CDATA[ Furnish Your Dream ]]></description>
    @foreach($products as $product)
    <item>
        <g:id>{{$product->product_code}}</g:id>
        <g:status>{{($product->is_active == 1 ? "active":"archived")}}</g:status>
        <g:title><![CDATA[{{ ucwords($product['title'])}}]]></g:title>

        <g:description><![CDATA[{{ preg_replace('/[^A-Za-z0-9\-]/', ' ',strip_tags($product['description']))}}]]></g:description>

        <g:link><![CDATA[https://regalfurniturebd.com/product/{{$product->seo_url}}]]></g:link>

        <g:product_type><![CDATA[{{property_exists('name', $product->category) ? $product->category->name : null}}]]></g:product_type>

         <g:google_product_category><![CDATA[436]]></g:google_product_category>
         <g:image_link><![CDATA[https://admin.regalfurniturebd.com/{{$product->firstImage->full_size_directory??null}}]]></g:image_link>
         <g:condition>new</g:condition>
         @if(isset($product->stock_status))<g:availability>{{($product->stock_status == 1 ? "in stock":"out of stock")}}</g:availability> @endif
        <g:price><![CDATA[{{number_format((float) ($product->local_selling_price??0),2,".","")." BDT"}}]]></g:price>
        <g:sale_price><![CDATA[{{number_format((float) ($product->product_price_now??0),2,".","")." BDT"}}]]></g:sale_price>
        <g:brand><![CDATA[RegalFurniture]]></g:brand>

    </item>

    @endforeach

    </channel>
</rss>
