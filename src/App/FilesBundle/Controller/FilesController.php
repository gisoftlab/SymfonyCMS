<?php

/*
 * This file is part of the Gisoft package.
 *
 * (c) Damian Ostraszewski
 *
 */

namespace App\FilesBundle\Controller;

use App\FilesBundle\Entity\Thumbnail;
use App\PagesBundle\Entity\Page;
use App\ProductBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\FilesBundle\Form\UploadType;
use App\FilesBundle\Entity\File;
use App\PagesBundle\Entity\PageFiles;
use App\ProductBundle\Entity\ProductFiles;
use App\CoreBundle\Controller\BaseController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\FilesBundle\Twig\FilesExtension;

/**
 * Backend Base controller.
 *
 * @author Damian Ostraszewski <info@gisoft.pl>   
 */
class FilesController extends BaseController {

    /**
     * @var string
     */
    protected $namespace = 'AppFilesBundle';
    /**
     * @var string
     */
    protected $module = 'Files';
    /**
     * @var string
     */
    protected $fieldName = "Plik";
    /**
     * @var string
     */
    protected $redirectShow = "";

    /**
    * Display upload Action
    *
    * @param Request $request     
    * @return Response
    */
    public function uploadAction(Request $request) {

        $id = $request->query->get('id') ? $request->query->get('id') : $request->get('PagesId');
        $feedback = ($request->query->get('feedback'))?$request->query->get('feedback'):$request->request->get('feedback');
        $quick = ($request->query->get('quick'))?$request->query->get('quick'):$request->request->get('quick');
        $iconUrl = null;

        if (!$feedback)
            $feedback = "#uploader";

        $form = $this->createForm(UploadType::class,array("id" => $id));
        if (Request::METHOD_POST == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $file = isset($data['sourceFile']) ? $data['sourceFile'] : null;
                $title = isset($data['sourceTitle']) ? $data['sourceTitle'] : "";
                $waterMark = isset($data['waterMark']) ? $data['waterMark'] : null;
                $id = isset($data['id']) ? $data['id'] : null;

                if($file) {
                    $model = $this->get("service.uploader")->Upload(
                        $file,
                        $title,
                        $id,
                        File::$FileContextPage,
                        $waterMark
                    );

                    /**
                     * @var Page $page
                     */
                    if ($page = $this->get("repo.page")->findOneBy(array("id" => $id))) {
                        $modPagesFiles = new PageFiles();
                        $modPagesFiles->setPage($page);
                        $modPagesFiles->setFile($model);
                        $this->get("repo.files")->save($modPagesFiles);
                        $modPagesFiles->setSequence($modPagesFiles->getId());
                        $this->get("repo.files")->save($modPagesFiles);

                        $icons = $page->getIcon();
                        if (!$icons) {
                            $page->setIcon($model);
                            $this->get("repo.files")->save($page);
                            $iconUrl = FilesExtension::getImage($model, Thumbnail::$ImageSizeSmaller);
                        }
                    }
                }
            }
        }


        return $this->render('AppFilesBundle:Files:upload.html.twig', array(
            'form' => $form->createView(),
            'id' => $id,
            'feedback' => $feedback,
            'quick' => $quick,
            'iconUrl' => $iconUrl,
            'files' => $this->get("repo.files")->retrieveFilesByPage($id)
        ));
    }

    /**
    * Display upload Product Form Action
    *
    * @param Request $request     
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function uploadProductAction(Request $request) {

        $id = $request->get('id');

        $feedback = $request->get('feedback');
        $quick = $request->get('quick');

        if (!$feedback)
            $feedback = "#Uploader";

        $uploadType = $this->get("form.files.upload");
        $uploadType->setID($id);
        $form = $this->createForm($uploadType);
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $file = isset($data['sourceFile']) ? $data['sourceFile'] : null;
                $title = isset($data['sourceTitle']) ? $data['sourceTitle'] : "";
                $waterMark = isset($data['waterMark']) ? $data['waterMark'] : null;
                $id = isset($data['id']) ? $data['id'] : $id;

                $model = $this->get("service.uploader")->Upload($file, $title, $id, File::$FileContextProduct, $waterMark);

                /**
                 * @var Product $product
                 */
                $product = $this->get("repo.product")->findOneBy(array("id" => $id));
                $modProductFiles = new ProductFiles();
                $modProductFiles->setProduct($product);
                $modProductFiles->setFile($model);
                $this->get("repo.files")->save($modProductFiles);
                $modProductFiles->setSequence($modProductFiles->getId());
                $this->get("repo.files")->save($modProductFiles);

                $Icons = $product->getIcon();
                if (!$Icons) {
                    $product->setIcon($model);
                    $this->get("repo.files")->save($product);
                }
            }
        }


        return $this->render('AppFilesBundle:Files:uploadProduct.html.twig', array(
                    'form' => $form->createView(),
                    'id' => $id,
                    'feedback' => $feedback,
                    'quick' => $quick,
                    'files' => $this->get("repo.files")->retrieveFilesByProduct($id)
        ));
    }

    /**
    * Display upload Product Promoted Action
    *
    * @param Request $request     
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function uploadProductPromotedAction(Request $request) {

        $id = $request->get('id');
        $product = null;
        $feedback = $request->get('feedback');
        $quick = $request->get('quick');
        $model = null;
        if (!$feedback)
            $feedback = "#Uploader";

        $uploadType = $this->get("form.files.upload");
        $uploadType->setID($id);
        $form = $this->createForm($uploadType);
        if ('POST' == $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $file = isset($data['sourceFile']) ? $data['sourceFile'] : null;
                $title = isset($data['sourceTitle']) ? $data['sourceTitle'] : "";
                $waterMark = isset($data['waterMark']) ? $data['waterMark'] : null;
                $id = isset($data['id']) ? $data['id'] : $id;

                $model = $this->get("service.uploader")->Upload($file, $title, $id, File::$FileContextProduct, $waterMark);

                /**
                 * @var Product $product
                 */
                $product = $this->get("repo.product")->findOneBy(array("id" => $id));
                $product->setIconPromoted($model);
                $this->get("repo.files")->save($product);
            }
        }


        return $this->render('AppFilesBundle:Files:uploadProductPromoted.html.twig', array(
                'form' => $form->createView(),
                'id' => $id,
                'feedback' => $feedback,
                'quick' => $quick,
                'file' => $model,
                'product' => $product
        ));
    }

    /**
     * Set PageIcon
     *
     * @param integer $id
     * @param integer $fileId
     * @return Response
     */
    public function setIconAction($id, $fileId) {

        $this->get("repo.files")->SetIcon($fileId, $id);

        return $this->render('AppFilesBundle:Files:_gallery.html.twig', array(
                    'files' => $this->get("repo.files")->retrieveFilesByPage($id),
                    'quick' => 0,
        ));
    }

    /**
     * Set ProductIcon
     *
     * @param integer $productId
     * @param integer $fileId
     * @return Response
     */
    public function setIconProductAction($id, $fileId) {

        $this->get("repo.files")->setIconProd($fileId, $id);

        return $this->render('AppFilesBundle:Files:_galleryProduct.html.twig', array(
                    'files' => $this->get("repo.files")->retrieveFilesByProduct($id),
                    'quick' => 0,
        ));
    }

    /**
     * Delete Action
     *
     * @param integer $id
     * @param integer $fileId
     * @return Response
     */
    public function deleteAction($id, $fileId) {

        /**
         * @var Page $page
         */
        $page = $this->get("repo.page")->findOneBy(array("id" => $id));
        /**
         * @var PageFiles $pageFiles
         */
        $pageFiles = $this->get("repo.pageFiles")->findOneBy(array("page" => $id, "file" => $fileId));

        if ($page->getIcon())
            if ($page->getIcon()->getId() == $pageFiles->getFile()->getId()) {
                $page->setIcon(null);
                $this->get("repo.page")->save($page);
            }

        $this->get("repo.pageFiles")->delete($pageFiles);
        $this->get("repo.files")->DeleteFile($fileId);


        return $this->render('AppFilesBundle:Files:_gallery.html.twig', array(
                    'files' => $this->get("repo.files")->retrieveFilesByPage($id),
                    'quick' => 0,
        ));
    }

    /**
     * Delete Product Action
     *
     * @param integer $id
     * @param integer $fileId
     * @return Response
     */
    public function deleteProductAction($id, $fileId) {

        /**
         * @var Product $product
         */
        $product = $this->get("repo.product")->findOneBy(array("id" => $id));
        /**
         * @var ProductFiles $productFiles
         */
        $productFiles = $this->get("repo.productFiles")->findOneBy(array("product" => $id, "file" => $fileId));

        if ($product->getIcon())
            if ($product->getIcon()->getId() == $productFiles->getFile()->getId()) {
                $product->setIcon(null);
                $this->get("repo.product")->save($product);
            }

        $this->get("repo.productFiles")->delete($productFiles);

        $this->get("repo.files")->DeleteFile($fileId);

        return $this->render('AppFilesBundle:Files:_galleryProduct.html.twig', array(
                    'files' => $this->get("repo.files")->retrieveFilesByProduct($id),
                    'quick' => 0,
        ));
    }

     /**
    * Edit Title Action
    *
    * @param Request $request
    * @return \Symfony\Component\HttpFoundation\Response
    */
    public function editTitleAction(Request $request) {

        if (Request::METHOD_POST == $request->getMethod()) {
            $post = $request->request->get("Title");

            $FilesId = $post["FilesId"];
            $FileTitle = $post["FileTitle"];

            $this->get("repo.files")->SaveTitle($FilesId, $FileTitle);

            return new Response("ok");
        } else
            throw new NotFoundHttpException('only ajax request');
    }
}
