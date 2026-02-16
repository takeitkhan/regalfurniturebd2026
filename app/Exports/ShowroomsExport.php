<?php

namespace App\Exports;

use App\Models\Post;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShowroomsExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Post::select(
            'id', 'user_id', 'title', 'sub_title', 'seo_url', 'author', 'description', 'short_description',
            'brand', 'phone', 'opening_hours', 'latitude', 'longitude', 'address',
            'division', 'district', 'thana', 'shop_type', 'is_active'
        )->whereIn('categories', array(651))->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'User ID',
            'Title',
            'Sub Title',
            'SEO URL',
            'Contact Person',
            'Description',
            'Short Description',
            'Brand',
            'Phone',
            'Opening Hours',
            'Latitude',
            'Longitude',
            'Address',
            'Division',
            'District',
            'Thana',
            'Shop Type',
            'Is Active',
        ];
    }
}
