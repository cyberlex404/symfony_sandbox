<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use App\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/page")
 *
 * @package App\Controller\Admin
 */
class PageController extends AbstractController
{
    /**
     * @Route("/", methods="GET", name="admin_index")
     * @Route("/", methods="GET", name="admin_page_index")
     */
    public function index(): Response
    {
        return $this->render('page/index.html.twig', [
            'controller_name' => 'PageController',
        ]);
    }

    /**
     * @Route("/new", methods="GET|POST", name="admin_page_new")
     */
    public function new(Request $request): Response
    {
        $page = new Page();
        // ...

        $form = $this->createForm(PageType::class, $page)
          ->add('saveAndCreateNew', SubmitType::class);


        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/forms.html#processing-forms
        if ($form->isSubmitted() && $form->isValid()) {
            $page = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($page);
            $em->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/controller.html#flash-messages
            $this->addFlash('success', 'page.created_successfully');

            if ($form->get('saveAndCreateNew')->isClicked()) {
                return $this->redirectToRoute('admin_page_new');
            }

            return $this->redirectToRoute('admin_page_index');
        }

        return $this->render('page/new.html.twig', [
          'page' => $page,
          'form' => $form->createView(),
        ]);
    }

}
