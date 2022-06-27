<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Layout;
use Illuminate\Database\QueryException;

class LayoutSeeder extends Seeder
{

    public function run()
    {
        try{
            Layout::create([
                'title' => 'Default',
                'path' => 'theme-default',
                'status' => '1',
                'structure' => '{
                    "tag": "div",
                    "class": "",
                    "image": "preview.jpg",
                    "label": "root",
                    "nodes": [
                        {
                            "tag": "div",
                            "class": "grid grid-cols-12",
                            "nodes": [
                                {
                                    "label": "Navbar widget",
                                    "section_id": "header-section-36",
                                    "class": "col-span-12",
                                    "tag": "div"
                                }
                            ]
                        },
                        {
                            "tag": "div",
                            "class": "container",
                            "nodes":[
                                {
                                    "class": "grid grid-cols-12 gap-2",
                                    "tag": "div",
                                    "nodes":[
                                        {
                                            "label": "Marquee widget",
                                            "section_id": "marquee-section-36",
                                            "class": "col-span-12",
                                            "tag": "div"
                                        }
                                    ]
                                },
                                {
                                    "class": "grid grid-cols-12 gap-2",
                                    "tag": "div",
                                    "nodes":[
                                        {
                                            "label": "Tag widget",
                                            "section_id": "tag-section-36",
                                            "class": "col-span-12",
                                            "tag": "div"
                                        }
                                    ]
                                },
                                {
                                    "class": "grid grid-cols-12 lg:gap-4 gap-2 py-2.5",
                                    "tag": "div",
                                    "nodes": [
                                        {
                                            "class": "col-span-12 lg:col-span-3 order-2 lg:order-1",
                                            "tag": "div",
                                            "nodes": [
                                                {
                                                    "class": "grid grid-cols-12 gap-2",
                                                    "tag": "div",
                                                    "nodes":[
                                                        {
                                                            "label": "Top widget",
                                                            "class": "col-span-12 lg:bg-violet-200 bg-transparent",
                                                            "tag": "div",
                                                            "section_id": "heading-content-section-one-36"
                                                        },
                                                        {
                                                            "label": "Top widget",
                                                            "class": "col-span-12",
                                                            "tag": "div",
                                                            "section_id": "heading-add-section-one-36"
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            "class": "col-span-12 lg:col-span-6 order-1 lg:order-2",
                                            "tag": "div",
                                            "nodes": [
                                                {
                                                    "class": "grid grid-cols-12",
                                                    "tag": "div",
                                                    "nodes":[
                                                        {
                                                            "label": "Top widget",
                                                            "class": "col-span-12",
                                                            "tag": "div",
                                                            "section_id": "heading-section-middle-top-36"
                                                        }
                                                    ]
                                                },
                                                {
                                                    "class": "grid grid-cols-12 gap-2",
                                                    "tag": "div",
                                                    "nodes":[
                                                        {
                                                            "label": "Bottom widget",
                                                            "class": "col-span-12",
                                                            "tag": "div",
                                                            "section_id": "heading-section-middle-bottom-36"
                                                        }
                                                    ]
                                                }
                                            ]
                                        },
                                        {
                                            "class": "col-span-12 lg:col-span-3 order-3 lg:order-3",
                                            "tag": "div",
                                            "nodes": [
                                                {
                                                    "class": "grid grid-cols-12 gap-2",
                                                    "tag": "div",
                                                    "nodes":[
                                                        {
                                                            "label": "Prayer widget",
                                                            "class": "col-span-12",
                                                            "tag": "div",
                                                            "section_id": "heading-third-section-prayer-36"
                                                        },
                                                        {
                                                            "label": "Motamot widget",
                                                            "class": "col-span-12",
                                                            "tag": "div",
                                                            "section_id": "heading-third-section-decision-36"
                                                        },
                                                        {
                                                            "label": "Add widget",
                                                            "class": "col-span-12",
                                                            "tag": "div",
                                                            "section_id": "heading-third-section-add-36"
                                                        }
                                                    ]
                                                }
                                            ] 
                                        }
                                    ]
                                },
                                {
                                    "class": "grid grid-cols-12 gap-4",
                                    "tag": "div",
                                    "nodes": [
                                        {
                                            "class": "lg:col-span-8 col-span-12",
                                            "tag": "div",
                                            "label": "Left widget",
                                            "section_id": "second-section-left-36"
                                        },
                                        {
                                            "class": "lg:col-span-4 col-span-12",
                                            "tag": "div",
                                            "nodes":[
                                                {
                                                    "class": "grid grid-cols-12 gap-1",
                                                    "tag": "div",
                                                    "nodes":[
                                                        {
                                                            "label": "Tab section",
                                                            "class": "col-span-12",
                                                            "tag": "div",
                                                            "section_id": "second-section-tab-archive-36"
                                                        },
                                                        {
                                                            "label": "Add section",
                                                            "class": "col-span-12 py-4",
                                                            "tag": "div",
                                                            "section_id": "second-section-add-36"
                                                        }
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                },
                                {
                                    "class": "grid grid-cols-12 gap-4 py-4",
                                    "tag": "div",
                                    "nodes":[
                                        {
                                            "class": "lg:col-span-8 col-span-12",
                                            "tag": "div",
                                            "label": "Left widget",
                                            "section_id": "third-section-left-36"
                                        },
                                        {
                                            "class": "lg:col-span-4 col-span-12",
                                            "tag": "div",
                                            "label": "Right widget",
                                            "section_id": "third-section-right-36"
                                        }
                                    ]
                                },
                                {
                                    "class": "grid grid-cols-12 gap-4",
                                    "tag": "div",
                                    "nodes":[
                                        {
                                            "class": "lg:col-span-8 col-span-12",
                                            "tag": "div",
                                            "label": "Left widget",
                                            "section_id": "fourth-section-left-36"
                                        },
                                        {
                                            "class": "lg:col-span-4 col-span-12",
                                            "tag": "div",
                                            "nodes":[
                                                {
                                                    "class": "grid grid-cols-12 gap-1",
                                                    "tag": "div",
                                                    "nodes":[
                                                        {
                                                            "class": "col-span-12",
                                                            "tag": "div",
                                                            "label": "District section",
                                                            "section_id": "fourth-district-section-right-36"
                                                        },
                                                        {
                                                            "class": "col-span-12",
                                                            "tag": "div",
                                                            "label": "Add section",
                                                            "section_id": "fourth-add-section-right-36"
                                                        }
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                },
                                {
                                    "class": "grid grid-cols-12 gap-4",
                                    "tag": "div",
                                    "nodes":[
                                        {
                                            "class": "lg:col-span-8 col-span-12",
                                            "tag": "div",
                                            "label": "Left widget",
                                            "section_id": "fifth-tab-section-36"
                                        },
                                        {
                                            "class": "lg:col-span-4 col-span-12",
                                            "tag": "div",
                                            "label": "Right widget",
                                            "section_id": "fifth-left-section-36"
                                        }
                                    ]
                                },
                                {
                                    "class": "grid grid-cols-12 gap-4",
                                    "tag": "div",
                                    "nodes":[
                                        {
                                            "class": "col-span-12",
                                            "tag": "div",
                                            "label": "Tab widget",
                                            "section_id": "sixth-highligts-tab-36"
                                        }
                                    ]
                                },
                                {
                                    "class": "grid grid-cols-12 gap-4",
                                    "tag": "div",
                                    "nodes":[
                                        {
                                            "class": "lg:col-span-6 col-span-12",
                                            "tag": "div",
                                            "label": "Left widget",
                                            "section_id": "seventh-left-section-36"
                                        },
                                        {
                                            "class": "lg:col-span-6 col-span-12",
                                            "tag": "div",
                                            "label": "Right widget",
                                            "section_id": "seventh-tab-section-36"
                                        }
                                    ]
                                },
                                {
                                    "class": "grid grid-cols-12 gap-4",
                                    "tag": "div",
                                    "nodes":[
                                        {
                                            "class": "lg:col-span-3 col-span-12",
                                            "tag": "div",
                                            "label": "Left widget",
                                            "section_id": "eighth-left-section-36"
                                        },
                                        {
                                            "class": "lg:col-span-6 col-span-12",
                                            "tag": "div",
                                            "label": "Middle widget",
                                            "section_id": "eighth-tab-section-36"
                                        },
                                        {
                                            "class": "lg:col-span-3 col-span-12",
                                            "tag": "div",
                                            "label": "Right widget",
                                            "section_id": "eighth-right-section-36"
                                        }
                                    ]
                                },
                                {
                                    "class": "grid grid-cols-12 gap-4",
                                    "tag": "div",
                                    "nodes":[
                                        {
                                            "class": "col-span-12",
                                            "tag": "div",
                                            "nodes":[
                                                {
                                                    "class": "grid grid-cols-6 gap-4 my-4 py-2 px-2 border-y-2 border-zinc-600",
                                                    "tag": "div",
                                                    "label": "Heading widget",
                                                    "section_id": "ninth-heading-section-36"
                                                },
                                                {
                                                    "class": "grid grid-cols-12 gap-4 py-2",
                                                    "tag": "div",
                                                    "nodes":[
                                                        {
                                                            "class": "col-span-12",
                                                            "tag": "div",
                                                            "label": "Main widget",
                                                            "section_id": "ninth-bottom-section-36"
                                                        }
                                                    ]
                                                }
                                            ]
                                        }
                                    ]
                                },
                                {
                                    "class": "grid grid-cols-12 gap-4",
                                    "tag": "div",
                                    "nodes":[
                                        {
                                            "class": "lg:col-span-3 col-span-12",
                                            "tag": "div",
                                            "label": "widget one",
                                            "section_id": "tenth-section-one-36"
                                        },
                                        {
                                            "class": "lg:col-span-3 col-span-12",
                                            "tag": "div",
                                            "label": "widget two",
                                            "section_id": "tenth-section-two-36"
                                        },
                                        {
                                            "class": "lg:col-span-3 col-span-12",
                                            "tag": "div",
                                            "label": "widget three",
                                            "section_id": "tenth-section-three-36"
                                        },
                                        {
                                            "class": "lg:col-span-3 col-span-12",
                                            "tag": "div",
                                            "label": "widget four",
                                            "section_id": "tenth-section-four-36"
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            "tag": "footer",
                            "class": "text-center lg:text-left bg-gray-100 text-gray-600",
                            "label": "Footer widget",
                            "section_id": "footer-section-36"
                        }
                        
                    ],
                    "section_id": "root",
                    "layout_name": "Default",
                    "layout_slug": "default"
                }'
            ]);
        }catch(QueryException $ex){
            echo "Error Found\n";
        }

        try{
            Layout::create([
                'title' => 'Voice TV',
                'path' => 'voice-tv',
                'status' => '0',
                'structure' => '{
                    "tag": "div",
                    "class": "",
                    "image": "preview.jpg", 
                    "label": "root", 
                    "nodes": [
                        {
                            "tag": "header", 
                            "class": "voice-header", 
                            "label": "Header", 
                            "section_id": "voice-header"
                        }, 
                        {
                            "tag": "section", 
                            "class": "container", 
                            "label": "Marquee", 
                            "section_id": "voice-marquee-section"}, {"tag": "section", "class": "container", "nodes": [{"tag": "div", "class": "row", "nodes": [{"tag": "div", "class": "col-3", "nodes": [{"tag": "div", "class": "row", "nodes": [{"tag": "div", "class": "col-12", "label": "Main Section 1", "section_id": "voice-main-1"}, {"tag": "div", "class": "col-12", "label": "Main Section 2", "section_id": "voice-main-2"}]}]}, {"tag": "div", "class": "col-5", "label": "Main Section 3", "section_id": "voice-main-3"}, {"tag": "div", "class": "col-4", "nodes": [{"tag": "div", "class": "row", "nodes": [{"tag": "div", "class": "col-12", "label": "Live TV", "section_id": "voice-live-tv"}, {"tag": "div", "class": "col-12", "label": "Main Section 4", "section_id": "voice-main-4"}]}]}]}]}, {"tag": "section", "class": "container", "nodes": [{"tag": "div", "class": "row", "nodes": [{"tag": "div", "class": "col-5", "label": "Two Column Two Rows News Widget", "section_id": "voice-two-col-two-row"}, {"tag": "div", "class": "col-4", "label": "সব খবর", "section_id": "voice-all-news"}, {"tag": "div", "class": "col-3", "label": "Advertisement Widget Mujib 100", "section_id": "voice-advertise"}]}]}, {"tag": "section", "class": "container", "nodes": [{"tag": "div", "class": "row", "nodes": [{"tag": "div", "class": "col-6", "label": "বিনোদন", "section_id": "voice-ent"}, {"tag": "div", "class": "col-3", "label": "অপরাধ", "section_id": "voice-crime"}, {"tag": "div", "class": "col-3", "label": "জেলার সংবাদ", "section_id": "voice-dist-news"}]}]}, {"tag": "section", "class": "container", "nodes": [{"tag": "div", "class": "row", "nodes": [{"tag": "div", "class": "col-6", "label": "রাজনীতি", "section_id": "voice-politics"}, {"tag": "div", "class": "col-3", "label": "সারাদেশ", "section_id": "voice-all-country"}, {"tag": "div", "class": "col-3", "label": "Advertisement widget", "section_id": "voice-advert"}]}]}, {"tag": "section", "class": "container", "label": "Advertisement Section", "section_id": "voice-adv-sec"}, {"tag": "section", "class": "container", "label": "ভিডিও সংবাদ", "section_id": "voice-video-news"}, {"tag": "section", "class": "container", "nodes": [{"tag": "div", "class": "row", "nodes": [{"tag": "div", "class": "col-3", "label": "বিশ্ব", "section_id": "voice-world"}, {"tag": "div", "class": "col-3", "label": "খেলার খবর", "section_id": "voice-sports"}, {"tag": "div", "class": "col-6", "label": "ছবি গ্যালারি", "section_id": "voice-gallery"}]}]}, {"tag": "footer", "class": "container", "label": "Footer", "section_id": "voice-footer"}
                    ], 
                    "section_id": "root", "layout_name": "Voice TV", "layout_slug": "voice-tv"
                }',
            ]);
        }catch(QueryException $ex){
            echo "Error Found\n";
        }
       
    }
}
