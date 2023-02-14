<?php

namespace App\Product\Domain\FileConverter\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

class RowDto
{
    private const FORMAT_DATE = 'd.m.Y';
    private const ERROR_MSG_POSITIVE = 'поле должно быть больше, чем 0';
    private const ERROR_MSG_NOT_BLANK = 'поле должно быть заполнено';
    private const ERROR_MSG_INCORRECT_FORMAT_DATE = 'период должен быть указан в формате день.месяц.год';
    private const ERROR_MSG_INCORRECT_TYPE = 'тип ТРУ заполнен неверно. Выберите из списка';

    #[SerializedName('A')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    private ?string $num;

    #[SerializedName('AX')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    private ?string $initiatorDepartmentName;

    #[SerializedName('AZ')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    private ?string $organizerDepartmentName;

    #[SerializedName('G')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    #[Assert\Choice(callback: 'getTypeValues', message: self::ERROR_MSG_INCORRECT_TYPE)]
    private ?string $type;

    #[SerializedName('F')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    private ?string $okpd2;

    #[SerializedName('H')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    private ?string $title;

    #[SerializedName('N')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    private ?string $okei;

    #[SerializedName('P')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    #[Assert\Positive(message: self::ERROR_MSG_POSITIVE)]
    private ?string $quantity;

    #[SerializedName('R')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    private ?string $deliveryBasis;

    #[SerializedName('S')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    #[Assert\Positive(message: self::ERROR_MSG_POSITIVE)]
    private ?string $purchasingCost;

    #[SerializedName('V')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    #[Assert\Positive(message: self::ERROR_MSG_POSITIVE)]
    private ?string $purchasingCostNds;

    #[SerializedName('AA')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    #[Assert\DateTime(format: self::FORMAT_DATE, message: self::ERROR_MSG_INCORRECT_FORMAT_DATE)]
    private ?string $deliveryPeriodStart;

    #[SerializedName('AB')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    #[Assert\DateTime(format: self::FORMAT_DATE, message: self::ERROR_MSG_INCORRECT_FORMAT_DATE)]
    private ?string $deliveryPeriodEnd;

    #[SerializedName('Y')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    #[Assert\DateTime(format: self::FORMAT_DATE, message: self::ERROR_MSG_INCORRECT_FORMAT_DATE)]
    private ?string $purchasingDate;

    #[SerializedName('AG')]
    #[Assert\NotBlank(message: self::ERROR_MSG_NOT_BLANK)]
    private ?string $purchasingMethod;

    #[SerializedName('AH')]
    private ?string $procurementClause;

    #[SerializedName('AT')]
    private ?string $contragentName;

    #[SerializedName('D')]
    private ?string $rnp;

    #[SerializedName('I')]
    private ?string $rnl;

    #[SerializedName('AW')]
    private ?string $companyName;

    public function __construct(
        ?string $num,
        ?string $initiatorDepartmentName,
        ?string $organizerDepartmentName,
        ?string $type,
        ?string $okpd2,
        ?string $title,
        ?string $okei,
        ?string $quantity,
        ?string $deliveryBasis,
        ?string $purchasingCost,
        ?string $purchasingCostNds,
        ?string $deliveryPeriodStart,
        ?string $deliveryPeriodEnd,
        ?string $purchasingDate,
        ?string $purchasingMethod,
        ?string $procurementClause,
        ?string $contragentName,
        ?string $rnp,
        ?string $rnl,
        ?string $companyName,
    )
    {
        $this->num = $num;
        $this->initiatorDepartmentName = $initiatorDepartmentName;
        $this->organizerDepartmentName = $organizerDepartmentName;
        $this->type = $type;
        $this->okpd2 = $okpd2;
        $this->title = $title;
        $this->okei = $okei;
        $this->quantity = $quantity;
        $this->deliveryBasis = $deliveryBasis;
        $this->purchasingCost = $purchasingCost;
        $this->purchasingCostNds = $purchasingCostNds;
        $this->deliveryPeriodStart = $deliveryPeriodStart;
        $this->deliveryPeriodEnd = $deliveryPeriodEnd;
        $this->purchasingDate = $purchasingDate;
        $this->purchasingMethod = $purchasingMethod;
        $this->procurementClause = $procurementClause;
        $this->contragentName = $contragentName;
        $this->rnp = $rnp;
        $this->rnl = $rnl;
        $this->companyName = $companyName;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function getInitiatorDepartmentName(): ?string
    {
        return $this->initiatorDepartmentName;
    }

    public function getOrganizerDepartmentName(): ?string
    {
        return $this->organizerDepartmentName;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getOkpd2(): ?string
    {
        return $this->okpd2;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getOkei(): ?string
    {
        return $this->okei;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function getDeliveryBasis(): ?string
    {
        return $this->deliveryBasis;
    }

    public function getPurchasingCost(): ?string
    {
        return $this->purchasingCost;
    }

    public function getPurchasingCostNds(): ?string
    {
        return $this->purchasingCostNds;
    }

    public function getDeliveryPeriodStart(): ?string
    {
        return $this->deliveryPeriodStart;
    }

    public function getDeliveryPeriodEnd(): ?string
    {
        return $this->deliveryPeriodEnd;
    }

    public function getPurchasingDate(): ?string
    {
        return $this->purchasingDate;
    }

    public function getPurchasingMethod(): ?string
    {
        return $this->purchasingMethod;
    }

    public function getProcurementClause(): ?string
    {
        return $this->procurementClause;
    }

    public function getContragentName(): ?string
    {
        return $this->contragentName;
    }

    public function getRnp(): ?string
    {
        return $this->rnp;
    }

    public function getRnl(): ?string
    {
        return $this->rnl;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }
}
