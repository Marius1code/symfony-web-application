<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MovementRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MovementRepository::class)]
class Movement
{
    public const TYPE_ENTRY = 'entry';
    public const TYPE_EXIT = 'exit';
    public const TYPE_TRANSFER = 'transfer';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\Column(type:"string", length:20)]
    #[Assert\Choice(choices: [self::TYPE_ENTRY, self::TYPE_EXIT, self::TYPE_TRANSFER])]
    private ?string $type = null;

    #[ORM\ManyToOne(targetEntity: Article::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    #[ORM\Column(type:"integer")]
    #[Assert\Positive]
    private ?int $quantity = null;

    #[ORM\Column(type:"datetime")]
    private \DateTimeInterface $date;

    #[ORM\Column(type:"string", length:255, nullable:true)]
    private ?string $notes = null;

    public function __construct()
    {
        $this->date = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }
}
