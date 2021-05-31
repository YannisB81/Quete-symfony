<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;
use Symfony\Component\HttpFoundation\Request;
/**
* @Route("/programs", name="program_")
*/
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response A responce instance
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();
        return $this->render('program/index.html.twig', [
        'programs' => $programs,
        ]);
    }
    /**
     * @Route("/new", name="new")
     */
    public function new(Request $request) : Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }
        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);
    }
    /**
     * @Route("/{id}", methods={"GET"}, requirements={"id"="\d+"}, name="show")
     */
    public function show(Program $program): Response
    {
        
        $programId = $program->getId();
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findByProgram($programId);
        if (!$seasons) {
            throw $this->createNotFoundException(
                'No season with this program found in season\'s table.'
            );
        }
        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
        ]);
    }
    /**
     * @Route("{program}/season/{season}", methods={"GET"}, requirements={"season"="\d+"}, name="season_show")
     */
    public function showSeason(Program $program, Season $season): Response
    {
        $programId = $program->getId();
        $seasonId = $season->getId();
        $episodes = $this->getDoctrine()
            ->getRepository(Episode::class)
            ->findBySeason($seasonId);
        if (!$episodes) {
            throw $this->createNotFoundException(
                'No episode with '.$episodeName.', found in episode\'s table.'
            );
        }
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
        ]);
    }
    /**
     * @Route("{program}/season/{season}/episodes/{episode}", methods={"GET"}, requirements={"episode"="\d+"}, name="episode_show")
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }
}