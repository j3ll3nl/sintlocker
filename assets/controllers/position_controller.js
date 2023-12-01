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
        let pauseUntill = Date.now() + 1000;
        let capturedBarcode = '';
        let backgroundField = this.backgroundTarget;
        let digitField = this.digitTarget;
        let lockField = this.lockTarget;
        let lockColumnField = this.lockColumnTarget;
        let currentBackgroundColor = 'yellow';

        let captureBarcodeHandler = async function (event) {
            pauseUntill = Date.now() + 10000;
            if (event.keyCode === 13) {
                let splittedBarcode = capturedBarcode.replace(/Clear/g, '').match(/[0-9]+|[a-z]/g);
                capturedBarcode = '';

                if (splittedBarcode === null || !splittedBarcode.length === 2) {
                    return;
                }

                pauseUntill = Date.now() + 10000;
                await setCombination(splittedBarcode[0], splittedBarcode[1]);

                return;
            }

            capturedBarcode += event.key;
        }

        async function setCombination(lock, lockColumn) {
            let combination = await getCombination(lock, lockColumn)

            if (combination === null) {
                return;
            }

            digitField.textContent = combination.digit;
            lockField.textContent = 'Slot: ' + combination.lock;
            lockColumnField.textContent = 'Rij: ' + combination.lockColumn;
            backgroundField.classList.replace('background-' + currentBackgroundColor, 'background-' + combination.color);
            currentBackgroundColor = combination.color;
        }

        async function getCombination(lock, lockColumn) {
            return await fetch(Routing.generate('app_locks_getcombination', {lock, lockColumn}))
                .then(function (res) {
                    if (!res.ok) {
                        return null;
                    }
                    return res.json();
                }).catch(function () {
                    return null;
                });
        }

        function randomizer() {
            setTimeout(function () {
                let lockColumn = getRandomChar();
                let lock = getRandomInt();

                if (Date.now() > pauseUntill) {
                    setCombination(lock, lockColumn);
                }

                randomizer();
            }, 100);
        }

        function getRandomChar() {
            const characters = 'abcdefghij';
            const charactersLength = characters.length;
            return characters.charAt(Math.floor(Math.random() * charactersLength));
        }

        function getRandomInt() {
            return Math.floor(Math.random() * 10) + 1;
        }

        window.addEventListener('keydown', captureBarcodeHandler, false);
        randomizer();
    }
}
