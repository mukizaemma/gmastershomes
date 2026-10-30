<style>
    .pay-request {
        max-width: 780px;
        margin: 0 auto 1.5rem;
        background: #fff;
        color: #111;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 14px;
        line-height: 1.45;
        padding: 28px 32px 36px;
        border: 1px solid #e6e6e6;
    }
    .pay-request__head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 24px;
    }
    .pay-request__logo { width: 168px; height: auto; margin-top: 18px; }
    .pay-request__issuer { margin-left: auto; text-align: right; white-space: nowrap; }
    .pay-request__company { display: block; font-size: 22px; font-weight: 700; margin-bottom: 4px; }
    .pay-request__rule { border: 0; border-top: 2px solid #c0392b; margin: 16px 0 18px; }
    .pay-request h1 { font-size: 26px; font-weight: 700; margin: 0 0 14px; }
    .pay-request h2 { font-size: 16px; font-weight: 700; margin: 22px 0 8px; }
    .pay-request__meta { margin: 0 0 2px; }
    .pay-request__note { margin: 14px 0 10px; }
    .pay-request__rate { margin: 0 0 8px; }
    .pay-request a { color: #1155cc; }
    .pay-request table { width: 100%; border-collapse: collapse; }
    .pay-request th, .pay-request td { border: 1px solid #222; padding: 6px 8px; vertical-align: top; }
    .pay-request thead th { background: #d9d9d9; font-weight: 700; }
    .pay-request .col-num { width: 36px; text-align: center; }
    .pay-request .col-amt { width: 130px; text-align: right; white-space: nowrap; }
    .pay-request__total td { font-weight: 700; }
    .pay-request__included { color: #666; font-size: 12px; margin-left: 6px; }
    .pay-request__pay-title { margin-top: 28px; }
    .pay-request__sign {
        display: flex;
        align-items: flex-end;
        gap: 48px;
        margin-top: 42px;
    }
    .pay-request__name { font-size: 16px; margin: 12px 0; }
    .pay-request__stamp { width: 120px; height: 120px; object-fit: contain; }
    @media print {
        .pay-request { border: 0; max-width: none; padding: 0; }
    }
</style>
