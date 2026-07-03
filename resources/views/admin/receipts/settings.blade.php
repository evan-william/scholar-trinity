<x-admin-shell
    title="E-Invoice Settings"
    subtitle="Prepare Taiwan e-invoice provider credentials and receipt tax behavior."
>
    <section class="card">
        <div class="section-title">
            <div>
                <h2>Provider Configuration</h2>
                <p>Keep secrets out of Git. Leave sensitive fields blank to keep existing values.</p>
            </div>
            <a class="btn light" href="{{ route('admin.receipts.index') }}">Back to Receipts</a>
        </div>
        <form method="POST" action="{{ route('admin.receipts.settings.update') }}">
            @csrf
            @method('PUT')
            <div class="filters" style="grid-template-columns:repeat(2,minmax(0,1fr))">
                <label>Provider
                    <select name="provider">
                        <option value="manual" @selected($setting->provider === 'manual')>Manual</option>
                        <option value="ecpay" @selected($setting->provider === 'ecpay')>ECPay</option>
                        <option value="newebpay" @selected($setting->provider === 'newebpay')>NewebPay</option>
                        <option value="ezpay" @selected($setting->provider === 'ezpay')>ezPay</option>
                        <option value="turnkey" @selected($setting->provider === 'turnkey')>Turnkey</option>
                    </select>
                </label>
                <label>Environment
                    <select name="environment">
                        <option value="sandbox" @selected($setting->environment === 'sandbox')>Sandbox</option>
                        <option value="production" @selected($setting->environment === 'production')>Production</option>
                    </select>
                </label>
                <label>Merchant ID<input name="merchant_id" value="{{ old('merchant_id', $setting->merchant_id) }}"></label>
                <label>Callback URL<input name="callback_url" value="{{ old('callback_url', $setting->callback_url) }}"></label>
                <label>API Key<input name="api_key" placeholder="Leave blank to keep existing"></label>
                <label>Hash Key<input name="hash_key" placeholder="Leave blank to keep existing"></label>
                <label>Hash IV<input name="hash_iv" placeholder="Leave blank to keep existing"></label>
            </div>
            <label style="display:flex;flex-direction:row;align-items:center;gap:8px">
                <input style="width:auto;min-height:auto" type="checkbox" name="late_fee_taxable" value="1" @checked($setting->late_fee_taxable)> Include late fee in receipt amount
            </label>
            <label style="display:flex;flex-direction:row;align-items:center;gap:8px">
                <input style="width:auto;min-height:auto" type="checkbox" name="allow_unpaid_receipts" value="1" @checked($setting->allow_unpaid_receipts)> Allow receipt issue before payment is paid
            </label>
            <label style="display:flex;flex-direction:row;align-items:center;gap:8px">
                <input style="width:auto;min-height:auto" type="checkbox" name="is_active" value="1" @checked($setting->is_active)> Active
            </label>
            <button class="btn" type="submit">Save Settings</button>
        </form>
    </section>
</x-admin-shell>
