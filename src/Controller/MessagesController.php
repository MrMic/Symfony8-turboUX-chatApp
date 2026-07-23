<?php

namespace App\Controller;

use App\Entity\Room;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MessagesController extends AbstractController
{
    #[Route('/rooms/{room}/messages/new', name: 'app_messages_new', methods: ['GET','POST'])]
    public function new(Room $room, Request $request): Response
    {
        $form = $this->createForm(MessageType::class);


        if ($form->isSubmitted() && $form->isValid()) {
            dd('cool');
        }
        return $this->render('messages/new.html.twig', [
            'room' => $room,
            'form' => $form->createView(),
        ]);
    }
}
