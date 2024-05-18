/**
 *  Form Wizard
 */

'use strict';

$(function () {
    const select2 = $('.select2'),
      selectPicker = $('.selectpicker');

    // Bootstrap select
    if (selectPicker.length) {
        selectPicker.selectpicker();
    }

    // select2
    if (select2.length) {
        select2.each(function () {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>');
            $this.select2({
                placeholder: 'Select value',
                dropdownParent: $this.parent()
            });
        });
    }
});
(function () {
    // Numbered Wizard
    // --------------------------------------------------------------------
    const wizardNumbered = document.querySelector('.wizard-numbered'),
      wizardNumberedBtnNextList = [].slice.call(wizardNumbered.querySelectorAll('.btn-next')),
      wizardNumberedBtnPrevList = [].slice.call(wizardNumbered.querySelectorAll('.btn-prev')),
      wizardNumberedBtnSubmit = wizardNumbered.querySelector('.btn-submit');

    if (typeof wizardNumbered !== undefined && wizardNumbered !== null) {
        const numberedStepper = new Stepper(wizardNumbered, {
            linear: false
        });
        if (wizardNumberedBtnNextList) {
            wizardNumberedBtnNextList.forEach(wizardNumberedBtnNext => {
                wizardNumberedBtnNext.addEventListener('click', event => {
                    numberedStepper.next();
                });
            });
        }
        if (wizardNumberedBtnPrevList) {
            wizardNumberedBtnPrevList.forEach(wizardNumberedBtnPrev => {
                wizardNumberedBtnPrev.addEventListener('click', event => {
                    numberedStepper.previous();
                });
            });
        }
        if (wizardNumberedBtnSubmit) {
            wizardNumberedBtnSubmit.addEventListener('click', event => {
                alert('Submitted..!!');
            });
        }
    }
//     2
    if (document.body.contains(document.querySelector('.wizard-numbered-2'))) {
        const wizardNumbered2 = document.querySelector('.wizard-numbered-2'),
          wizardNumbered2BtnNextList = [].slice.call(wizardNumbered2.querySelectorAll('.btn-next')),
          wizardNumbered2BtnPrevList = [].slice.call(wizardNumbered2.querySelectorAll('.btn-prev')),
          wizardNumbered2BtnSubmit = wizardNumbered2.querySelector('.btn-submit');

        if (typeof wizardNumbered2 !== undefined && wizardNumbered2 !== null) {
            const numberedStepper = new Stepper(wizardNumbered2, {
                linear: false
            });
            if (wizardNumbered2BtnNextList) {
                wizardNumbered2BtnNextList.forEach(wizardNumbered2BtnNext => {
                    wizardNumbered2BtnNext.addEventListener('click', event => {
                        numberedStepper.next();
                    });
                });
            }
            if (wizardNumbered2BtnPrevList) {
                wizardNumbered2BtnPrevList.forEach(wizardNumbered2BtnPrev => {
                    wizardNumbered2BtnPrev.addEventListener('click', event => {
                        numberedStepper.previous();
                    });
                });
            }
            if (wizardNumbered2BtnSubmit) {
                wizardNumbered2BtnSubmit.addEventListener('click', event => {
                    alert('Submitted..!!');
                });
            }
        }
    }
})();
