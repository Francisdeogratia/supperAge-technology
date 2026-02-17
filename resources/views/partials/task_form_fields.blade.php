<div class="row">
    <div class="col-md-3">
        <label>Price</label>
        <input type="number" name="price" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label>Currency</label>
        <select name="currency" class="form-control">
            <option value="NGN">Nigerian Naira (NGN)</option>
            <option value="USD">US Dollar (USD)</option>
            <option value="GBP">British Pound (GBP)</option>
            <option value="EUR">Euro (EUR)</option>
            <option value="GHS">Ghanaian Cedi (GHS)</option>
            <option value="KES">Kenyan Shilling (KES)</option>
            <option value="TZS">Tanzanian Shilling (TZS)</option>
            <option value="UGX">Ugandan Shilling (UGX)</option>
            <option value="ZAR">South African Rand (ZAR)</option>
            <option value="XAF">Central African CFA Franc (XAF)</option>
            <option value="XOF">West African CFA Franc (XOF)</option>
            <option value="CAD">Canadian Dollar (CAD)</option>
            <option value="CLP">Chilean Peso (CLP)</option>
            <option value="COP">Colombian Peso (COP)</option>
            <option value="EGP">Egyptian Pound (EGP)</option>
            <option value="GNF">Guinean Franc (GNF)</option>
            <option value="MWK">Malawian Kwacha (MWK)</option>
            <option value="MAD">Moroccan Dirham (MAD)</option>
            <option value="RWF">Rwandan Franc (RWF)</option>
            <option value="SLL">Sierra Leonean Leone (SLL)</option>
            <option value="STD">São Tomé Dobra (STD)</option>
            <option value="ZMW">Zambian Kwacha (ZMW)</option>
        </select>
    </div>
    <div class="col-md-3">
        <label>Target Count</label>
        <input type="number" name="target" class="form-control" required>
    </div>
    <div class="col-md-3">
        <label>Duration (days)</label>
        <input type="number" name="duration" class="form-control" required>
    </div>
</div>
<div class="mt-2">
    <label>Countries, Gender, Age</label>
    <input type="text" name="audience" class="form-control" placeholder="e.g. Nigeria, Male, 18-35">
</div>
<button type="submit" class="btn btn-outline-{{ $color }} mt-2">{{ $button }}</button>
