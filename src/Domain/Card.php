<?php

declare(strict_types=1);

namespace Domain;

// This is ugly but we can do an exception
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


// should be immutable
#[ORM\Entity]
class Card
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public readonly ?int $id;

    #[ORM\Column(length: 255)]
    public readonly string $question;

    #[ORM\Column(length: 255)]
    public readonly string $answer;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    public readonly ?\DateTimeInterface $initialTestDate;

    #[ORM\Column]
    public readonly ?bool $active;

    #[ORM\Column(length: 255, nullable: true)]
    public readonly ?string $image;

    #[ORM\Column(options: ['default' => 0])]
    public readonly int $delay;

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setDelay(int $delay): self
    {
        $this->delay = $delay;

        return $this;
    }

    public function setInitialTestDate(?\DateTimeInterface $initialTestDate): self
    {
        $this->initialTestDate = $initialTestDate;

        return $this;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
