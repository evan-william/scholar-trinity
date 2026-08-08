<?php

namespace App\Services;

use App\Models\RegistrationPricingTier;
use Illuminate\Support\Collection;
use Throwable;

class RegistrationPricingService
{
    public function tiers(): Collection
    {
        try {
            return RegistrationPricingTier::query()
                ->where('is_active', true)
                ->orderBy('exam_count')
                ->get();
        } catch (Throwable) {
            return collect();
        }
    }

    public function tierFor(int $examCount): ?RegistrationPricingTier
    {
        if ($examCount < 1) {
            return null;
        }

        $tiers = $this->tiers();

        return $tiers->firstWhere('exam_count', $examCount)
            ?? $tiers->where('exam_count', '<=', $examCount)->sortByDesc('exam_count')->first();
    }

    public function breakdown(int $examCount, int $fallbackExamTotal, int $fallbackServiceTotal): array
    {
        $tier = $this->tierFor($examCount);
        $examFee = $tier?->exam_fee_per_exam ?? ($examCount > 0 ? intdiv($fallbackExamTotal, $examCount) : 0);
        $serviceFee = $tier?->service_fee_per_exam ?? ($examCount > 0 ? intdiv($fallbackServiceTotal, $examCount) : 0);

        return [
            'uses_tier' => $tier !== null,
            'exam_fee_per_exam' => $examFee,
            'service_fee_per_exam' => $serviceFee,
            'combined_fee_per_exam' => $examFee + $serviceFee,
            'exam_fee_total' => $tier ? $examFee * $examCount : $fallbackExamTotal,
            'service_fee_total' => $tier ? $serviceFee * $examCount : $fallbackServiceTotal,
            'combined_total' => $tier ? ($examFee + $serviceFee) * $examCount : $fallbackExamTotal + $fallbackServiceTotal,
            'currency' => $tier?->currency ?? 'NTD',
        ];
    }
}
