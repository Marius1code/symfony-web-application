<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\Repository\InvoiceRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
class Invoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type:"integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Client::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull]
    private ?Client $client = null;

    #[ORM\Column(type:"datetime")]
    private \DateTimeInterface $date;

    #[ORM\OneToMany(mappedBy: "invoice", targetEntity: InvoiceLine::class, cascade: ["persist", "remove"], orphanRemoval: true)]
    #[Assert\Valid]
    private Collection $lines;

    public function __construct()
    {
        $this->date = new \DateTimeImmutable();
        $this->lines = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

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

    /**
     * @return Collection|InvoiceLine[]
     */
    public function getLines(): Collection
    {
        return $this->lines;
    }

    public function addLine(InvoiceLine $line): self
    {
        if (!$this->lines->contains($line)) {
            $this->lines[] = $line;
            $line->setInvoice($this);
        }

        return $this;
    }

    public function removeLine(InvoiceLine $line): self
    {
        if ($this->lines->removeElement($line)) {
            // set the owning side to null (unless already changed)
            if ($line->getInvoice() === $this) {
                $line->setInvoice(null);
            }
        }

        return $this;
    }

    public function getTotal(): float
    {
        $total = 0.0;
        foreach ($this->lines as $line) {
            $total += $line->getTotal();
        }
        return $total;
    }
}
