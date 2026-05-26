<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder\IO;

/**
 * Collects filter resolution metadata for explain() output.
 */
class FilterExplainSnapshot
{
    /**
     * @var array<int, array{field: string, condition: string|null, clause: string, values: mixed}>
     */
    private array $applied = [];

    /**
     * @var array<int, array{field: string, values: mixed, reason: string}>
     */
    private array $skipped = [];

    public function reset(): void
    {
        $this->applied = [];
        $this->skipped = [];
    }

    public function recordApplied(string $field, ?string $condition, string $clauseClass, mixed $values): void
    {
        $this->applied[] = [
            'field' => $field,
            'condition' => $condition,
            'clause' => $clauseClass,
            'values' => $values,
        ];
    }

    public function recordSkipped(string $field, mixed $values, string $reason): void
    {
        $this->skipped[] = [
            'field' => $field,
            'values' => $values,
            'reason' => $reason,
        ];
    }

    /**
     * @return array{applied: array, skipped: array}
     */
    public function toArray(): array
    {
        return [
            'applied' => $this->applied,
            'skipped' => $this->skipped,
        ];
    }

    /**
     * @return array<int, string>
     */
    public function getAppliedFields(): array
    {
        return array_column($this->applied, 'field');
    }
}
