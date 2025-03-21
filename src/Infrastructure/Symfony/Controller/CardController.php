<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Controller;

use Application\CreateCardUseCase;
use Application\SolveCardUseCase;
use Domain\Card;
use Domain\CardRepositoryInterface;
use Domain\FileHandlerInterface;
use Infrastructure\PersistenceAdapter;
use Infrastructure\Symfony\Form\CardType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CardController extends AbstractController
{
    public function __construct(
        private readonly CardRepositoryInterface $cardRepository,
        private readonly PersistenceAdapter $persistenceAdapter,
        private readonly FileHandlerInterface $fileHandler,
        private readonly SolveCardUseCase $handleCardSolving,
        private readonly CreateCardUseCase $createCardUseCase,
    ) {
    }

    #[Route('/', name: 'app_card')]
    public function index(): Response
    {
        return $this->render('card/index.html.twig', [
            'controller_name' => 'CardController',
            'cards' => $this->cardRepository->findBy([], ['active' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'app_card_new', methods: [Request::METHOD_POST, Request::METHOD_GET])]
    public function newCard(Request $request): Response
    {
        $form = $this->createForm(CardType::class, new Card(), ['method' => Request::METHOD_POST]);

        return $this->handleCardForm($form, $request);
    }

    #[Route('/edit/{id}', name: 'app_card_patch', methods: [Request::METHOD_PATCH, Request::METHOD_GET])]
    public function editCard(Card $card, Request $request): Response
    {
        $form = $this->createForm(CardType::class, $card, ['method' => Request::METHOD_PATCH]);
        // you should use a UseCase
        $oldCard = $this->cardRepository->findOneBy(['id' => $card->id]);

        return $this->handleCardForm($form, $request, $oldCard?->getImage());
    }

    #[Route('/card/{id}', name: 'app_card_delete', methods: [Request::METHOD_DELETE])]
    public function deleteCard(Card $card): Response
    {
        // you should use a UseCase
        $this->cardRepository->remove($card);
        $this->persistenceAdapter->flush();

        return $this->redirectToRoute('app_card');
    }

    #[Route('/cards/test', name: 'app_cards_test', methods: [Request::METHOD_GET])]
    public function listCardsToTest(): Response
    {
        // you should use a UseCase
        $cardsToTest = $this->cardRepository->findTodayCards();

        return $this->render('card/test.html.twig', ['cards' => iterator_to_array($cardsToTest)]);
    }

    #[Route('/card/solve/{id}', name: 'app_card_solve', methods: [Request::METHOD_POST])]
    public function solveCard(Card $card, Request $request): Response
    {
        $answer = $request->request->get('answer');
        $isSolved = $this->handleCardSolving->execute($card, (string) $answer);

        $this->addFlash(
            $isSolved ? 'success' : 'danger',
            $isSolved ? 'Bonne réponse !' : 'Mauvaise réponse ! À demain pour vous tester à nouveau sur cette carte',
        );

        return $this->redirectToRoute('app_cards_test');
    }

    private function handleCardForm(FormInterface $form, Request $request, ?string $existingImg = null): Response
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var ?UploadedFile $imgFile */
            $imgFile = $form->get('image')->getData();

            /** @var Card $card */
            $card = $form->getData();
            try {
                $this->createCardUseCase->handle($card, $imgFile, $existingImg);
            } catch (FileException $e) {
                return $this->render('card/form.html.twig', [
                    'form' => $form,
                    'error' => $e->getMessage(),
                ]);
            }

            return $this->redirectToRoute('app_card');
        }

        return $this->render('card/form.html.twig', ['form' => $form]);
    }
}
