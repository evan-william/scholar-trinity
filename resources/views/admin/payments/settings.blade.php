<x-admin-shell
    title="Payment Settings"
    subtitle="Configure manual bank transfer details and prepare ECPay or NewebPay gateway credentials."
>
    <section class="card">
        <div class="section-title">
            <div>
                <h2>Gateway and Manual Payment</h2>
                <p>Credentials should be filled on the server only. Leave sensitive fields blank to keep existing values.</p>
            </div>
            <a class="btn light" href="{{ route('admin.payments.index') }}">Back to Payments</a>
        </div>

        <form method="POST" action="{{ route('admin.payments.settings.update') }}">
            @csrf
            @method('PUT')
            <div class="filters" style="grid-template-columns:repeat(2,minmax(0,1fr))">
                <label>Provider
                    <select name="provider">
                        <option value="manual" @selected($setting->provider === 'manual')>Manual</option>
                        <option value="ecpay" @selected($setting->provider === 'ecpay')>ECPay</option>
                        <option value="newebpay" @selected($setting->provider === 'newebpay')>NewebPay</option>
                    </select>
                </label>
                <label>Mode
                    <select name="mode">
                        <option value="sandbox" @selected($setting->mode === 'sandbox')>Sandbox</option>
                        <option value="production" @selected($setting->mode === 'production')>Production</option>
                    </select>
                </label>
                <label>Merchant ID
                    <input name="merchant_id" value="{{ old('merchant_id', $setting->merchant_id) }}">
                </label>
                <label>Payment Deadline Days
                    <input type="number" name="payment_deadline_days" value="{{ old('payment_deadline_days', $setting->payment_deadline_days ?: 7) }}">
                    <span class="hint">The deadline is calculated from the registration submission date plus this number of days.</span>
                </label>
                <label>Hash Key
                    <input name="hash_key" placeholder="Leave blank to keep existing">
                </label>
                <label>Hash IV
                    <input name="hash_iv" placeholder="Leave blank to keep existing">
                </label>
                <label>Callback URL
                    <input name="callback_url" value="{{ old('callback_url', $setting->callback_url) }}">
                </label>
                <label>Return URL
                    <input name="return_url" value="{{ old('return_url', $setting->return_url) }}">
                </label>
                <label>Success URL
                    <input name="success_url" value="{{ old('success_url', $setting->success_url) }}">
                </label>
                <label>Failed URL
                    <input name="failed_url" value="{{ old('failed_url', $setting->failed_url) }}">
                </label>
                <label>Bank Name
                    <input name="bank_name" value="{{ old('bank_name', $setting->bank_name) }}">
                    <span class="hint">Shown in public bank-transfer instructions and payment emails.</span>
                </label>
                <label>Bank Code
                    <input name="bank_code" value="{{ old('bank_code', $setting->bank_code) }}">
                </label>
                <label>Bank Branch
                    <input name="bank_branch" value="{{ old('bank_branch', $setting->bank_branch) }}">
                </label>
                <label>Account Name
                    <input name="account_name" value="{{ old('account_name', $setting->account_name) }}">
                </label>
                <label>Account Number
                    <input name="account_number" value="{{ old('account_number', $setting->account_number) }}">
                </label>
            </div>
            <label>Manual Instruction
                <textarea name="manual_instruction">{{ old('manual_instruction', $setting->manual_instruction) }}</textarea>
            </label>
            <label style="display:flex;flex-direction:row;align-items:center;gap:8px">
                <input style="width:auto;min-height:auto" type="checkbox" name="is_active" value="1" @checked($setting->is_active)> Active
            </label>
            <button class="btn" type="submit">Save Settings</button>
        </form>
    </section>

    <section class="card">
        <div class="section-title">
            <div>
                <h2>Unified Registration Pricing</h2>
                <p>The selected exam count determines one adjusted NTD rate. Service Fee is calculated automatically as Total minus Exam Cost.</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.payments.pricing.update') }}">
            @csrf
            @method('PUT')
            <p class="hint">Set the unified price by exam count. Exam Cost is the test-center portion; Service Fee is calculated automatically from Total minus Exam Cost.</p>
            <div style="overflow-x:auto">
                <table>
                    <thead><tr><th># of Exams</th><th>Per Exam (USD)</th><th>NTD (adjusted rate)</th><th>Total</th><th>Exam Cost</th><th>Service Fee</th><th>TS Service Fee per subject</th><th>Active</th></tr></thead>
                    <tbody>
                    @foreach($pricingTiers as $index => $tier)
                        @php
                            $total = $tier->combined_fee_per_exam * $tier->exam_count;
                            $examCost = $tier->exam_fee_per_exam * $tier->exam_count;
                            $serviceFee = $total - $examCost;
                        @endphp
                        <tr data-pricing-row>
                            <td><input type="number" name="tiers[{{ $index }}][exam_count]" value="{{ $tier->exam_count }}" min="1" max="20" required></td>
                            <td><input type="number" name="tiers[{{ $index }}][reference_usd_per_exam]" value="{{ $tier->reference_usd_per_exam }}" min="0" placeholder="Optional"></td>
                            <td><input type="number" name="tiers[{{ $index }}][combined_fee_per_exam]" value="{{ $tier->combined_fee_per_exam }}" min="0" required></td>
                            <td data-pricing-total>{{ number_format($total) }}</td>
                            <td><input type="number" name="tiers[{{ $index }}][exam_cost_total]" value="{{ $examCost }}" min="0" required></td>
                            <td data-pricing-service>{{ number_format($serviceFee) }}</td>
                            <td data-pricing-service-unit>{{ number_format($tier->service_fee_per_exam) }}</td>
                            <td><input type="hidden" name="tiers[{{ $index }}][currency]" value="{{ $tier->currency }}"><input type="hidden" name="tiers[{{ $index }}][is_active]" value="0"><input style="width:auto;min-height:auto" type="checkbox" name="tiers[{{ $index }}][is_active]" value="1" @checked($tier->is_active)></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <p class="hint">Current structure lowers the unified per-exam amount as students select more exams, with the configured floor applied from six exams onward.</p>
            <button class="btn" type="submit">Save Unified Pricing</button>
        </form>
    </section>

    <script>
        document.querySelectorAll('[data-pricing-row]').forEach((row) => {
            const count = row.querySelector('[name$="[exam_count]"]');
            const adjusted = row.querySelector('[name$="[combined_fee_per_exam]"]');
            const examCost = row.querySelector('[name$="[exam_cost_total]"]');
            const totalOutput = row.querySelector('[data-pricing-total]');
            const serviceOutput = row.querySelector('[data-pricing-service]');
            const serviceUnitOutput = row.querySelector('[data-pricing-service-unit]');
            const format = new Intl.NumberFormat('en-US', { maximumFractionDigits: 0 });

            const refresh = () => {
                const examCount = Math.max(Number(count.value) || 0, 1);
                const total = (Number(adjusted.value) || 0) * examCount;
                const service = total - (Number(examCost.value) || 0);
                totalOutput.textContent = format.format(total);
                serviceOutput.textContent = format.format(service);
                serviceUnitOutput.textContent = format.format(service / examCount);
            };

            [count, adjusted, examCost].forEach((input) => input.addEventListener('input', refresh));
        });
    </script>
</x-admin-shell>
