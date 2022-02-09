<?php

namespace App\Controller;

use App\Entity\Matching;
use App\Entity\Offer;
use App\Entity\User;
use App\Form\OfferType;
use App\Form\ProfileType;
use App\Form\StudentQuestionsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/etudiant/questionnaire", name="questions_student")
     */
    public function questionsStudent(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(StudentQuestionsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();
            $matchingRepository = $em->getRepository(Matching::class);

            if (null !== $form->getData()['cvFile']) {
                $user->setCvFile($form->getData()['cvFile']);
            }

            $studentTags = [];

            foreach ($form->getData() as $value) {
                if(is_array($value)) {
                    $studentTags[] = $value;
                }
            }

            $studentTags = call_user_func_array('array_merge', $studentTags);

            if (null !== $form->getData()['abroad']) {
                $studentTags[] = $form->getData()['abroad'];
            }

            if (null !== $form->getData()['remote']) {
                $studentTags[] = $form->getData()['remote'];
            }

            if (null !== $form->getData()['places']) {
                $studentTags[] = $form->getData()['places'];
            }

            $user->setQuestionnary($studentTags);
            $em->persist($user);

            $offers = $em->getRepository(Offer::class)->findBy([
                'provided' => false,
            ]);

            foreach ($offers as $offer) {
                $matchCount = 0;

                if (null != $offer->getQuestionnary()) {
                    foreach ($studentTags as $tag) {
                        if (in_array($tag, $offer->getQuestionnary())) {
                            $matchCount++;
                        }
                    }

                    $matching = $matchingRepository->findOneBy([
                        'offer' => $offer,
                        'student' => $user
                    ]);

                    $matchPercentage = ($matchCount / count($offer->getQuestionnary())) * 100;

                    if ($matchPercentage >= 75) {
                        if (null === $matching) {
                            $matching = new Matching();
                            $matching->setOffer($offer)
                                ->setStudent($user)
                                ->setPercentage($matchPercentage);
                        } else {
                            $matching->setPercentage($matchPercentage);
                        }

                        $em->persist($matching);
                    } elseif ($matchPercentage < 75 && null !== $matching) {
                        $em->remove($matching);
                    }
                }
            }

            $em->flush();

            return $this->redirectToRoute('default_student');
        }

        return $this->render('question/student.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/entreprise/questionnaire", name="questions_company_new")
     */
    public function questionsCompany(Request $request): Response
    {
        $company = $this->getUser();

        $offer = new Offer();
        $offer->setCompany($company);
        $offer->setPublishedAt(new \DateTime('now'));

        $form = $this->createForm(OfferType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $matchingRepository = $em->getRepository(Matching::class);

            $offer->setTitle($form->getData()['title']);

            if (null !== $form->getData()['offerFile']) {
                $offer->setOfferFile($form->getData()['offerFile']);
            }

            if (null !== $form->getData()['link']) {
                $offer->setLink($form->getData()['link']);
            }

            $offerTags = [];

            foreach ($form->getData() as $value) {
                if(is_array($value)) {
                    $offerTags[] = $value;
                }
            }

            $offerTags = call_user_func_array('array_merge', $offerTags);

            if (null !== $form->getData()['abroad']) {
                $offerTags[] = $form->getData()['abroad'];
            }

            if (null !== $form->getData()['remote']) {
                $offerTags[] = $form->getData()['remote'];
            }

            if (null !== $form->getData()['places']) {
                $offerTags[] = $form->getData()['places'];
            }

            $offer->setQuestionnary($offerTags);

            if (in_array('Période 01', $offerTags)) {
                $offer->setType(Offer::TYPE_WORKSTUDY);
            } else {
                $offer->setType(Offer::TYPE_INTERNSHIP);
            }

            $em->persist($offer);

            $students = $em->getRepository(User::class)->findBy([
                'type' => 'student',
            ]);

            foreach ($students as $student) {
                $matchCount = 0;

                if (null != $student->getQuestionnary()) {
                    foreach ($student->getQuestionnary() as $tag) {
                        if (in_array($tag, $offer->getQuestionnary())) {
                            $matchCount++;
                        }
                    }

                    $matching = $matchingRepository->findOneBy([
                        'offer' => $offer,
                        'student' => $student
                    ]);

                    $matchPercentage = ($matchCount / count($offer->getQuestionnary())) * 100;

                    if ($matchPercentage >= 75) {
                        if (null === $matching) {
                            $matching = new Matching();
                            $matching->setOffer($offer)
                                ->setStudent($student)
                                ->setPercentage($matchPercentage);
                        } else {
                            $matching->setPercentage($matchPercentage);
                        }

                        $em->persist($matching);
                    } elseif ($matchPercentage < 75 && null !== $matching) {
                        $em->remove($matching);
                    }
                }
            }

            $em->flush();

            return $this->redirectToRoute('match_and_offers');
        }

        return $this->render('question/company.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}