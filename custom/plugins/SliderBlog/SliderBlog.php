<?php

namespace SliderBlog;

use Shopware\Components\Plugin;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Models\Blog\Blog;


class SliderBlog extends Plugin
{


	public static function getSubscribedEvents()
    {
		return [
           'Enlight_Controller_Action_PostDispatch_Frontend_Blog' => 'SliderBlog'
		];
	}

    public function SliderBlog(\Enlight_Event_EventArgs $args)
    {

    	$controller = $args->get('subject');
    	$request = $controller->Request();
        $view = $controller->View();
        $view->addTemplateDir(__DIR__ .'/Resources/views/');

        if($controller->Request()->getActionName() === 'detail')
        {
            $em = $this->container->get('models');
            // $ms = $this->container->get('shopware_media.media_service');
            $Article = $view->getAssign('sArticle');
            $mediaId = $Article['media'][0]['mediaId'];

            if(!empty($Article))
            {
                $blogRepository = $em->getRepository(Blog::class);
                $ArticlesCat = $blogRepository->findBy(['categoryId' => $Article['categoryId']]);
                

                $blogArticle = [];
                foreach($ArticlesCat as $article)
                {
                    
                    // $medias = Shopware()->Models()->getRepository('Shopware\Models\Media\Media')->findOneBy(['id' => $mediaId]);
                    // $path = $medias->getPath();
                    // $mediaURL = $ms->getUrl($path);

                    echo '<pre>';
                    print_r(get_class_methods(get_class($article->getMedia())));
                    echo '</pre>';

                    $medias = $article->getMedia();
                    $image = $medias[0]->getMedia()->getPath();

                    if($Article['id'] != $article->getId())
                    {
                        $blogArticle[] = [
                            'title' => $article->getTitle(),
                            'image' => $image
                        ];
                    }
                    // echo '<pre>';
                    // var_dump(get_class_methods(get_class($blogRepository)));
                    // var_dump($image);
                    // echo '</pre>';
                }
                $view->assign('blogSlider', $blogArticle);
            }
            

            

            echo '<pre>';

            echo 'Get Class Method:';

            var_dump(get_class_methods(get_class($blogRepository)));

            echo '</br>';

            echo 'Var Dump: ';

            var_dump($ms);
              
            echo '</pre>';



            /*$blogArticleId = (int) $request->getQuery('blogArticle');
            $categoryId = (int) $request->getQuery('sCategory');
            $titleBlogArticlegetRepository = Shopware()->Models()->getRepository('Shopware\Models\Blog\Blog');
            $imagePathBlogArticleRepository = Shopware()->Models()->getRepository('Shopware\Models\Media\Media');
            $titleBlogArticle = $titleBlogArticlegetRepository->find((int)$blogArticleId);
            $BlogGetTitle = $titleBlogArtilce->getTitle();*/



            // $titleBlogArticlesFromCategory = $titleBlogArticlegetRepository->findBy(array('categoryId' => $categoryId));
            // $mediaService = $this->container->get('shopware_media.media_service');

            //$this == \SliderBlog\SliderBlog
            //$controller == Instanz der Eventklasse, in diesem Fall Shopware_Controllers_Frontend_Blog

            // $imagePath26 = $imagePathBlogArticleRepository->find(26)->getPath();
            // $imagePath27 = $imagePathBlogArticleRepository->find(27)->getPath();    // 1.Möglichkeit
            // $imagePath28 = $imagePathBlogArticleRepository->find(28)->getPath();
            // $imagePath29 = $imagePathBlogArticleRepository->find(29)->getPath();

            // $imagePath26 = $request->$blogArticle['media'][0][26];
            // $imagePath26 = $request->$blogArticle['media'][0]['mediaId'];
            // $imagePath26 = $request->$blogArticle['media'][0]['mediaId'];           // 2.Möglichkeit
            // $imagePath26 = $request->$blogArticle['media'][0]['mediaId'];

            /*$imagePath26 = $mediaService->getUrl('media/image/dad-909510_1920.jpg');
            $imagePath27 = $mediaService->getUrl('media/image/customer-experience-3024488_1920.jpg');
            $imagePath28 = $mediaService->getUrl('media/image/time-for-a-change-2015164_1920.jpg');    // 3.Möglichkeit
            $imagePath29 = $mediaService->getUrl('media/image/architecture-2256489_1920.jpg');*/



            /*$imagePath = [

                    'image' => $imagePath26,
                    'image' => $imagePath27,
                    'image' => $imagePath28,
                    'image' => $imagePath29

            ];


            $blogArticlePro = [

                    ['title' => 'Title', 'image' => $imagePath['image']]

            ];



            foreach ($titleBlogArticlesFromCategory as $BlogTitel) {
                foreach($blogArticlePro as $key => $value) {

                    $value['title'] = $BlogTitel->getTitle();
                    echo $title[$key] = $value['title'].' || ';
                    print_r($image[$key] = $value['image'].'<p></p>');
                    echo '</br>';

                }*/

        // }




// https://www.tutorialspoint.com/php/php_arrays.htm


            /*echo '</br>';
            echo '</br>';
            echo '</br>';
            echo '</br>';
            echo '</br>';
            echo '</br>';*/

        //     $blogArticle = [

        //             ['title' => 'Title'],
        //             ['image' => $imagePath26],
        //             ['image' => $imagePath27],
        //             ['image' => $imagePath28],
        //             ['image' => $imagePath29]

        // ];



        //    foreach ($titleBlogArticlesFromCategory as $BlogTitel) {
        //       foreach ($blogArticle as $key => $value) {

        //             $value['title'] = $BlogTitel->getTitle();
        //             echo $title[$key] = $value['title'].' || '.PHP_EOL;
        //             echo $image[$key] = $value['image'].'<p></p>';

        //         }
        //     }


            /*echo PHP_EOL;


            $view->assign('blogArticleId', $blogArticleId);
            $view->assign('CategoryIdFromBlogArticle', $categoryId);
            $view->assign('BlogGetTitle', $BlogGetTitle);
            $view->assign('BlogGetTitles', $titleBlogArticlesFromCategory);
            $view->assign('BlogGetMedia', $mediaBlogGetMedia);
            $view->assign('BlogPicture', $getMedia);



            echo '</br>';
            echo $controller->Request()->getActionName();
            echo '<pre>';
            var_dump(get_class_methods(get_class($imagePath26)));
            echo '</pre>';*/
        // }

      
   // $mediaBlogRepository = Shopware()->Models()->getRepository('Shopware\Models\Blog\Media');
  // $mediaBlogId = $mediaBlogRepository->findOneBy(array('blogId' => $blogArticleId));
 // $mediaBlogGetMedia = $mediaBlogId->getId();


        }
    }
}