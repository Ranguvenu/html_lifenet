import $ from 'jquery';
import * as Repository from './repository';
import Ajax from 'core/ajax';
import Config from 'core/config';
import Log from 'core/log';
import ModalFactory from 'core/modal_factory';
import ModalEvents from 'core/modal_events';
import Templates from 'core/templates';
import {get_string as getString} from 'core/str';

/**
 * Creates and shows a modal that contains a placeholder.
 * @param {object} airtelConfig
 *
 * @returns {Promise<Modal>}
 */
const showModalWithPlaceholder = async(airtelConfig) => {
    const modal = await ModalFactory.create({
        body: await Templates.render('paygw_airtelafrica/placeholder', airtelConfig)
    });
    modal.show();
    return modal;
};

/**
 * Process the payment.
 *
 * @param {string} component Name of the component that the itemId belongs to
 * @param {string} paymentArea The area of the component that the itemId belongs to
 * @param {number} itemId An internal identifier that is used by the component
 * @param {string} description Description of the payment
 * @returns {Promise<string>}
 */
export const process = (component, paymentArea, itemId, description) => {
    return Promise.all([
        Repository.getConfigForJs(component, paymentArea, itemId),
    ])
    .then(([airtelConfig]) => {
        return Promise.all([
            showModalWithPlaceholder(airtelConfig),
        ])
        .then(([modal]) => {
            modal.setTitle(getString('pluginname', 'paygw_airtelafrica'));
            modal.getRoot().on(ModalEvents.hidden, () => {
                // Destroy when hidden.
                console.log('Destroy modal');    // eslint-disable-line
                modal.destroy();
            });
            return Promise.all([modal, airtelConfig]);
        });
    })
    .then(([modal, airtelConfig]) => {
        var cancelButton = modal.getRoot().find('#airtel-cancel');
        cancelButton.on('click', function() {
            modal.destroy();
        });
        var payButton = modal.getRoot().find('#airtel-pay');
        payButton.removeAttr('disabled');
        payButton.on('click', function(e) {
            e.preventDefault();
            var radioval = $("input[name='airtelnumber']:checked").val();
            var mobilenumber = $("input[name='mobilenumber']").val();
            var phone = (airtelConfig.phone) ? (radioval =='other') ? mobilenumber : airtelConfig.phone : mobilenumber;
            Promise.all([
                Repository.transactionStart(component, paymentArea, itemId, phone),
            ])
            .then(([airtelPay]) => {
                const transId = airtelPay.transactionid;
                modal.setBody(Templates.render('paygw_airtelafrica/busy', {
                    "sesskey": Config.sesskey,
                    "phone": phone,
                    "country": airtelConfig.country,
                    "component": component,
                    "paymentarea": paymentArea,
                    "transactionid": transId,
                    "itemid": itemId,
                    "description": description,
                    "reference": airtelConfig.reference,
                }));
                cancelButton = modal.getRoot().find('#airtel-cancel');
                cancelButton.on('click', function() {
                    e.preventDefault();
                    modal.destroy();
                });
                payButton = modal.getRoot().find('#airtel-pay');
                payButton.on('click', function() {
                    modal.destroy();
                });
                payButton.attr('disabled', true);
                console.log('Airtel Africa payment process started');  // eslint-disable-line
                console.log('Transaction id: ' + transId);  // eslint-disable-line
                var arrayints = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
                var interval = airtelConfig.timeout;
                var cont = true;
                console.log(modal); // eslint-disable-line
                arrayints.forEach(function(el, index) {
                    setTimeout(function() {
                        if (cont == true) {
                            const progressDiv = modal.getRoot().find('#airtel-progress_bar');
                            progressDiv.attr('value', el * 10);
                            payButton.attr('disabled', true);
                            if (transId != '') {
                                modal.setFooter(el + '/10');
                                Ajax.call([{
                                    methodname: "paygw_airtelafrica_transaction_complete",
                                    args: {
                                        component,
                                        paymentarea: paymentArea,
                                        itemid: itemId,
                                        transactionid: transId,
                                    },
                                    done: function(airtelPing) {
                                        console.log(el + '/10 ' + airtelPing.message);  // eslint-disable-line
                                        console.log(airtelPing.message); // eslint-disable-line
                                        if (airtelPing.message == "Transaction Success") {
                                            cont = (el == 1)? true : false;
                                            var payButton = modal.getRoot().find('#airtel-pay');
                                            payButton.on('click', function() {
                                                const loc = window.location.href;
                                                window.location.replace(loc);
                                            });
                                            progressDiv.attr('value', 100);
                                            modal.setFooter('Transaction with id ' + transId + ' succeeded');
                                            const spinnerElement = modal.getRoot().find('#airtel-spinner');
                                            spinnerElement.attr('style', 'display: none;');
                                            const a = '<br/><div class="p-3 mb-2 text-success font-weight-bold">';
                                            const outDiv = modal.getRoot().find('#airtel-out');
                                            if (cont == false) {
                                                outDiv.append(a + airtelPing.message + '</div>');
                                                payButton.removeAttr('disabled');
                                            }
                                        }
                                        if (airtelPing.message == "Transaction Failed") {
                                            cont = false;
                                            const a = '<br/><div class="p-3 mb-2 bg-danger text-white font-weight-bold">';
                                            modal.setBody(a + airtelPing.message + '</div>');
                                            modal.setFooter('Transaction with id ' + transId + ' failed');
                                            const spinnerElement = modal.getRoot().find('#airtel-spinner');
                                            console.log(spinnerElement);  // eslint-disable-line
                                            spinnerElement.hide();
                                        }
                                    },
                                    fail: function(e) {
                                        console.log('Airtel Africa payment failed');  // eslint-disable-line
                                        Log.debug(e);
                                    }
                                }]);

                                if (el > 9) {
                                    modal.destroy();
                                }
                            }
                        }
                    }, index * interval);
                });
                return new Promise(() => null);
            }).catch(e => {
                // We want to use promise reject here - as that's what core payment stuff expects.
                modal.setBody(getString('unable', 'paygw_airtelafrica'));
                console.log('Airtel Africa payment rejected');  // eslint-disable-line
                Log.debug('Airtel Africa payment rejected');
                Log.debug(e);
            });
        });
        return new Promise(() => null);
    }).catch(e => {
        Log.debug('Global error.');
        Log.debug(e);
        return Promise.reject(e.message);
    });
};
