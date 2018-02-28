<?php

namespace App\PagesBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use App\PagesBundle\Entity\Page as Page;

class PageData extends AbstractFixture implements OrderedFixtureInterface {

    public function load(ObjectManager $manager) {

        
        $description = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed aliquet lectus quam. Mauris accumsan dapibus purus, at tempor justo aliquet id. Praesent in justo vitae lorem convallis facilisis. Curabitur gravida congue semper. In vitae mi nisi. Praesent hendrerit facilisis sem eget mollis. Donec lobortis congue mauris, non dignissim nisi ultrices nec. Pellentesque ornare tortor eget mauris venenatis id consequat nunc eleifend. Nam placerat ipsum eget nulla sodales et placerat sapien hendrerit. Ut elementum mi eget dolor interdum sed facilisis diam suscipit. Duis blandit pulvinar facilisis. Praesent non urna eleifend magna convallis luctus a eget nunc. 
                               Nunc convallis, augue in imperdiet mattis, mauris leo viverra lectus, et tristique lorem nisi vitae sem. Suspendisse vestibulum aliquet elit, vitae feugiat magna aliquam in. Curabitur accumsan, augue at vulputate ultrices, lacus mi luctus felis, at facilisis augue ipsum quis odio. Quisque sit amet laoreet augue. Nunc sit amet sapien sit amet enim venenatis imperdiet. Duis imperdiet posuere odio, eget bibendum risus semper quis. Donec a pretium dui. Suspendisse eleifend sapien id urna imperdiet hendrerit id eu orci. Curabitur tincidunt purus erat. Fusce porta scelerisque sapien nec ornare. 
                               Sed lectus justo, dignissim et tempus egestas, sagittis sit amet risus. Curabitur tincidunt sagittis venenatis. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Morbi ac augue arcu, sit amet auctor magna. Curabitur euismod neque nec odio accumsan volutpat. Nam congue placerat diam at ultrices. Donec ligula metus, semper quis dapibus convallis, imperdiet vitae velit. Maecenas non lorem nec justo blandit facilisis. Nunc euismod rutrum feugiat. Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Integer adipiscing faucibus aliquet. Vestibulum vitae pretium risus. Curabitur a dui eget justo varius interdum vel eu ligula. 
                               Aliquam erat volutpat. Nulla ut enim dui, a mollis mi. Nullam vulputate eleifend urna eu congue. In auctor lacinia hendrerit. Duis vel dolor nisl, ac congue urna. Suspendisse id tortor ante. Nullam ullamcorper laoreet nunc, ut laoreet sapien semper sed. Nulla eget nibh sit amet lacus tristique porttitor. Praesent dui velit, dapibus nec accumsan vel, facilisis vitae purus. Phasellus et sodales odio. Cras sem orci, lacinia in sagittis mattis, suscipit nec nulla. 
                               Sed sit amet mi aliquet turpis aliquet varius in et massa. Fusce volutpat condimentum sapien et porttitor. Ut sed tellus felis, nec scelerisque velit. In id erat felis, ac dapibus tellus. Aenean facilisis convallis faucibus. Vestibulum interdum lectus vitae erat luctus ornare. Nam et luctus massa.";
        
        /*
         *  ------------------------------------
         */
        
        $data = new Page();
        $data->setTitle("Main");
        $data->setPublished(false);        
        $data->makeSlug();
        $data->setCreatedAtValue();                           
        $manager->persist($data);        
        
        /*
         *  ------------------------------------
         */
        
        $subdata = new Page();
        $subdata->setTitle('Strona główna');
        $subdata->setDescription($description);
        $subdata->setPublished(true);
        $subdata->setParent($data);                        
        $subdata->makeSlug();
        $subdata->setCreatedAtValue();                                   
        $manager->persist($subdata);      
        
        $subdata = new Page();
        $subdata->setTitle('Kontakt');
        $subdata->setDescription($description);
        $subdata->setPublished(true);
        $subdata->setParent($data);                        
        $subdata->makeSlug();
        $subdata->setCreatedAtValue();                                   
        $manager->persist($subdata);                     
                        
        /*
         *  ------------------------------------
         */
                            
        $manager->flush();
        
                                                                                                
    }

    public function getOrder() {
        return 5; // the order in which fixtures will be loaded
    }

}
