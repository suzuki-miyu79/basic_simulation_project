document.addEventListener("DOMContentLoaded", function () {
    const yearMonthInput = document.getElementById("year_month");

    yearMonthInput.addEventListener("change", function () {
        // 年月が変更されたときにフォームをサブミット
        this.form.submit();
    });
});