<?php

namespace Razoyo\AnimalProfile\Model\Attribute\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

class AnimalProfile extends AbstractSource
{
    const ANIMAL_CAT = 1;
    const ANIMAL_DOG = 2;
    const ANIMAL_LLAMA = 3;
    const ANIMAL_ANTEATER = 4;

    /**
     * {@inheritdoc}
     */
    public function getAllOptions($withEmpty = false, $defaultValues = false)
    {
        if (!$this->_options) {
            $this->_options = [
                [
                    'value' => self::ANIMAL_CAT,
                    'label' => __('Cat'),
                ], [
                    'value' => self::ANIMAL_DOG,
                    'label' => __('Dog'),
                ], [
                    'value' => self::ANIMAL_LLAMA,
                    'label' => __('Llama'),
                ], [
                    'value' => self::ANIMAL_ANTEATER,
                    'label' => __('Anteater'),
                ],
            ];

            if ($withEmpty) {
                array_unshift($this->_options, ['label' => ' ', 'value' => '']);
            }
        }
        
        return $this->_options;
    }

    public function toArray($withEmpty = true, $defaultValues = false)
    {
        $array = [];

        foreach ($this->getAllOptions($withEmpty, $defaultValues) as $option) {
            $array[$option["value"]] = $option["label"];
        }

        return $array;
    }
}
