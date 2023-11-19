import { Controller } from '@hotwired/stimulus';
import Routing from 'fos-router';

/*
* The following line makes this controller "lazy": it won't be downloaded until needed
* See https://github.com/symfony/stimulus-bridge#lazy-controllers
*/
/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['background', 'digit', 'lock', 'lockColumn', 'barcode'];

    connect() {
        let capturedBarcode = '';
        let backgroundField = this.backgroundTarget;
        let barcodeField = this.barcodeTarget;
        let digitField = this.digitTarget;
        let lockField = this.lockTarget;
        let lockColumnField = this.lockColumnTarget;
        let currentBackgroundColor = 'yellow';

        let captureBarcodeHandler = async function (event) {
            if (event.keyCode === 13) {
                barcodeField.textContent = capturedBarcode;
                let splittedBarcode = capturedBarcode.match(/[0-9]+|[a-z]/g);
                capturedBarcode = '';

                if (splittedBarcode === null || !splittedBarcode.length === 2) {
                    return;
                }

                let combination = await getCombination(splittedBarcode[0], splittedBarcode[1])

                if (combination === null) {
                    return;
                }

                digitField.textContent = combination.digit;
                lockField.textContent = 'Slot: ' + combination.lock;
                lockColumnField.textContent = 'Rij: ' + combination.lockColumn;
                backgroundField.classList.replace('background-' + currentBackgroundColor, 'background-' + combination.color);
                currentBackgroundColor = combination.color;
                return;
            }

            capturedBarcode += event.key;
        }

        async function getCombination(lock, lockColumn) {
            return await fetch(Routing.generate('app_locks_getcombination', {lock, lockColumn}))
                .then(function (res) {
                    if (!res.ok) {
                        return null;
                    }
                    return res.json();
                });
        }

        window.addEventListener('keydown', captureBarcodeHandler, false);
    }
}
