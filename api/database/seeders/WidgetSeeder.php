<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Widget;
use Illuminate\Support\Facades\DB;

class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   
        DB::insert("INSERT INTO `widgets` 
        (`name`, `path`, `widget_type`, `taxonomy_selectable`, `taxonomy`, `selected_category`, `category_taxonomy`, `limit_required`, `title_required`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
        ('Navbar Auto Shrink on Scroll', 'WidgetsNavbar2', 'header', 0, NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL),
        ('Marquee', 'WidgetsMarquee', 'marquee', 0, 'post', 0, NULL, 0, 0, 1, NULL, NULL, NULL),
        ('Tag', 'WidgetsTag', 'tag', 0, 'post', 0, NULL, 0, 0, 1, NULL, NULL, NULL),
        ('Single Post Image Top', 'WidgetsSinglePostImageTop', 'single_post', 1, 'post', 0, NULL, 0, 0, 1, NULL, NULL, NULL),
        ('Decision', 'WidgetsDecision', 'single_post', 1, 'post', 0, NULL, 0, 0, 1, NULL, NULL, NULL),
        ('Single Post Image Right', 'WidgetsSinglePostImageRight', 'single_post', 1, 'post', 0, NULL, 0, 0, 1, NULL, NULL, NULL),
        ('Navbar Simple Elegant', 'WidgetsNavbar', 'header', 0, NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL),
        ('Footer', 'WidgetsFooter', 'footer', 0, NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL),
        ('Prayer', 'WidgetsPrayer', 'prayer', 0, NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL),
        ('SquareAdd', 'WidgetsSquareAdd', 'squareadd', 0, NULL, 0, NULL, 0, 0, 1, NULL, NULL, NULL),
        ('Multi Post Last and Archive News', 'WidgetsLastAndArchiveColumnsList', 'multi_post', 1, 'post', 0, NULL, 1, 1, 1, NULL, NULL, NULL),
        ('Left Two columns Image & Right ten Multi list', 'WidgetsLeftPostRightMultiListColumns', 'multi_post', 1, 'post', 0, NULL, 1, 1, 1, NULL, NULL, NULL),
        ('Left Two rows Image & Right five Multi list', 'WidgetsLeftTwoPostRightMultiList', 'multi_post', 1, 'post', 0, NULL, 1, 1, 1, NULL, NULL, NULL),
        ('Image with title and background color', 'WidgetsMultiPostBackgroundColumns', 'multi_post', 1, 'post', 0, NULL, 1, 1, 1, NULL, NULL, NULL),
        ('Multi Post image with title and subtitle columns', 'WidgetsMultiPostColumns', 'multi_post', 1, 'post', 0, NULL, 1, 1, 1, NULL, NULL, NULL),
        ('Left Four Image with overly title and right Multi List columns', 'WidgetsOverlayImageWithListColumns', 'multi_post', 1, 'post', 0, NULL, 1, 1, 1, NULL, NULL, NULL),
        ('Tabs Left four image columns and Right Multi list', 'WidgetsTabsFourColumnsRightLists', 'multi_post', 1, 'post', 1, 'post', 1, 1, 1, NULL, NULL, NULL),
        ('Tabs middle Heighlights image and left right multi list', 'WidgetsTabsHeighlightImageColumns', 'multi_post', 1, 'post', 1, 'post', 1, 1, 1, NULL, NULL, NULL),
        ('Tabs Left Two image with title right Multi List', 'WidgetsTabsLeftTwoPostRightMultiList', 'multi_post', 1, 'post', 1, 'post', 1, 1, 1, NULL, NULL, NULL),
        ('Tabs Multi List Post', 'WidgetsTabsMultiList', 'multi_post', 1, 'post', 1, 'post', 1, 1, 1, NULL, NULL, NULL),
        ('Top Heighlights Image and bottom multi List', 'WidgetsTopPostBottomMultiPostList', 'multi_post', 1, 'post', 0, NULL, 1, 1, 1, NULL, NULL, NULL),
        ('List Post : Multi list', 'WidgetsMultiPostListImageInset', 'multi_post', 1, 'post', 0, NULL, 1, 1, 1, NULL, NULL, NULL),
        ('Align Multi List Post', 'WidgetsCloserMultiList', 'multi_post', 1, 'post', 0, NULL, 1, 1, 1, NULL, NULL, NULL);
        ");
    }
}
