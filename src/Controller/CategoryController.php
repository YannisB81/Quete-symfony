<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/categories", name="category_")
 */

class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_index');
        }
        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }
    /**
     * @Route("/{categoryName}", methods={"GET"}, name="show")
     */
    public function show(string $categoryName): Response
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException(('No parameter has been sent to find a category'));
        }
        $category = $this->getDoctrine()
            ->getRepository(Category::Class)
            ->findOneBy(['name' => $categoryName]);
        if(!$category) {
            throw $this->createNotFoundException(
                'No category with id : '.$id.' found in category\'s table.'
            );
        }
        $categoryId = $category->getId();
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findByCategory($categoryId, ['id' => 'DESC'], 3);
        if (!$programs) {
            throw $this->createNotFoundException(
                'No program with '.$categoryName.', found in program\'s table.'
            );
        }
        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            'categoryName' => $categoryName,
        ]);
    }
}
