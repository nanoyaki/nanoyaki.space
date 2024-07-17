<?php

namespace App\Controller;

use App\Entity\BlockedEmailData;
use App\Exception\EmailBlockedException;
use App\Form\BlockEmailType;
use App\Service\BlocklistService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlocklistController extends AbstractController
{
    #[Route('/unsubscribe', name: 'app_unsubscribe')]
    public function block(
        Request $request,
        BlocklistService $blocklistService
    ): Response
    {
        $form = $this->createForm(BlockEmailType::class, new BlockedEmailData());

        $email = $request->query->has('email') ? $request->query->getString('email') : null;
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            assert($data instanceof BlockedEmailData);

            $email = $data->getEmail();
        }

        if ($email !== null) {
            try {
                $blocklistService->block($email);
            } catch (EmailBlockedException) {
                $this->addFlash(
                    'warning',
                    'That email is already on the blocklist'
                );

                return $this->render('block_list/index.html.twig', [
                    'controller_name' => 'BlockListController',
                ]);
            }

            $this->addFlash(
                'success',
                'You have successfully unsubscribed and will no longer receive emails from this service'
            );
        }

        return $this->render('block_list/index.html.twig', [
            'blockEmailForm' => $form,
        ]);
    }
}
