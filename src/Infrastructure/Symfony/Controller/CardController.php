<?php

declare(strict_types=1);

namespace Infrastructure\Symfony\Controller;

use Application\CardsAvailableToTestUseCase;
use Application\CreateCardUseCase;
use Application\DeleteCardUseCase;
use Application\FindOneCardUseCase;
use Application\SolveCardUseCase;
use Application\UpdateCardUseCase;
use Domain\CardRepositoryInterface;
use Infrastructure\Symfony\Http\Requests\CreateCardRequest;
use Infrastructure\Symfony\Http\Requests\TestCardDto;
use Infrastructure\Symfony\Http\Requests\UpdateCardRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CardController extends AbstractController
{
    public function __construct(
        private readonly CardRepositoryInterface $cardRepository,
        private readonly SolveCardUseCase $handleCardSolving,
        private readonly CreateCardUseCase $createCardUseCase,
        private readonly UpdateCardUseCase $updateCardUseCase,
        private readonly DeleteCardUseCase $deleteCardUseCase,
        private readonly CardsAvailableToTestUseCase $cardsAvailableToTestUseCase,
        private readonly FindOneCardUseCase $findOneCardUseCase,
        private readonly ObjectMapperInterface $objectMapper,
        private readonly ValidatorInterface $validator,
    ) {
    }

    #[Route('/', name: 'app_card', methods: [Request::METHOD_GET])]
    public function index(): JsonResponse
    {
        $cards = $this->cardRepository->listAllCards();

        return $this->json([
            'cards' => $cards,
        ]);
    }

    #[Route('/new', name: 'app_card_new', methods: [Request::METHOD_POST])]
    public function newCard(#[MapRequestPayload] CreateCardRequest $createCardRequest): JsonResponse
    {
        $violations = $this->validator->validate($createCardRequest);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $this->json([
                'error' => 'Validation failed',
                'violations' => $errors,
            ], Response::HTTP_BAD_REQUEST);
        }

        $card = $this->objectMapper->map($createCardRequest);
        $this->createCardUseCase->execute($card);

        return $this->json([
            'message' => 'Card created successfully',
            'card' => $card,
        ], Response::HTTP_CREATED);
    }

    #[Route('/edit/{id}', name: 'app_card_patch', methods: [Request::METHOD_PATCH])]
    public function editCard(string $id, #[MapRequestPayload] UpdateCardRequest $updateCardRequest): JsonResponse
    {
        $violations = $this->validator->validate($updateCardRequest);

        if (count($violations) > 0) {
            $errors = [];
            foreach ($violations as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $this->json([
                'error' => 'Validation failed',
                'violations' => $errors,
            ], Response::HTTP_BAD_REQUEST);
        }

        // Create a new UpdateCardRequest with the id parameter
        $updatedRequest = new UpdateCardRequest(
            $id,
            $updateCardRequest->question,
            $updateCardRequest->answer,
            $updateCardRequest->initialTestDate,
            $updateCardRequest->active
        );

        $card = $this->objectMapper->map($updatedRequest);
        $this->updateCardUseCase->execute($card);

        return $this->json([
            'message' => 'Card updated successfully',
            'card' => $card,
        ]);
    }

    #[Route('/card/{id}', name: 'app_card_delete', methods: [Request::METHOD_DELETE])]
    public function deleteCard(string $id): JsonResponse
    {
        $this->deleteCardUseCase->execute($id);

        return $this->json([
            'message' => 'Card deleted successfully',
        ]);
    }

    #[Route('/cards/test', name: 'app_cards_test', methods: [Request::METHOD_GET])]
    public function listCardsToTest(): JsonResponse
    {
        $cardsToTest = $this->cardsAvailableToTestUseCase->execute();
        $testCards = [];

        foreach ($cardsToTest as $card) {
            $testCards[] = new TestCardDto(
                $card->id ?? '',
                $card->question,
            );
        }

        return $this->json([
            'cards' => $testCards,
        ]);
    }

    #[Route('/card/solve/{id}', name: 'app_card_solve', methods: [Request::METHOD_POST])]
    public function solveCard(string $id, Request $request): JsonResponse
    {
        $card = $this->findOneCardUseCase->execute($id);

        if ($card === null) {
            return $this->json([
                'error' => 'Card not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $answer = $data['answer'] ?? '';

        $isSolved = $this->handleCardSolving->execute($card, (string) $answer);

        return $this->json([
            'solved' => $isSolved,
            'message' => $isSolved ? 'Correct answer!' : 'Wrong answer! Try again tomorrow.',
        ]);
    }
}
