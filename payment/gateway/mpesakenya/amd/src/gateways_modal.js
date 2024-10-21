// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This module is responsible for MPEsa Kenya content in the gateways modal.
 *
 * @copyright  2023 Medical Access Uganda Limited
 * @author     Renaat Debleu <info@eWallah.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
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
 *
 * @param {mpesaConfig} mpesaConfig
 *
 * @returns {Promise<Modal>}
 */
const showModalWithPlaceholder = async(mpesaConfig) => {
    console.table(mpesaConfig);
    const modal = await ModalFactory.create({
        body: await Templates.render('paygw_mpesakenya/placeholder', mpesaConfig)
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
    .then(([mpesaConfig]) => {        
        // const modal = await showModalWithPlaceholder(mpesaConfig);
        console.log(mpesaConfig);
        return Promise.all([
        showModalWithPlaceholder(mpesaConfig),
    ])
    .then(([modal]) => {        
        modal.setTitle(getString('pluginname', 'paygw_mpesakenya'));      
        // const userCountry = modal.getRoot().find('#mpesa-country');
        // userCountry.append('<h4>' + mpesaConfig.usercountry + '</h4>');
        // const extraDiv = modal.getRoot().find('#mpesa-extra');
        // extraDiv.append('<h4>' + mpesaConfig.cost + ' ' + mpesaConfig.currency + '</h4>');
        modal.getRoot().on(ModalEvents.hidden, () => {
            // Destroy when hidden.
            modal.destroy();
        });
        return Promise.all([modal, mpesaConfig]);
    });
        
    })
    .then(([modal, mpesaConfig]) => {
        var cancelButton = modal.getRoot().find('#mpesa-cancel');
        cancelButton.on('click', function() {
            modal.destroy();
        });
        var payButton = modal.getRoot().find('#mpesa-pay');
        payButton.removeAttr('disabled');
        payButton.on('click', function(e) {
            e.preventDefault();
            var radioval = $("input[name='mpesanumber']:checked").val();
            var phone = (radioval =='other')? $("input[name='mobilenumber']").val() : mpesaConfig.phone;
            Promise.all([
                Repository.transactionStart(component, paymentArea, itemId, phone),
            ])
            .then(([mpesaPay]) => {
                const merchantrequestid = mpesaPay.merchantrequestid;
                modal.setBody(Templates.render('paygw_mpesakenya/busy', {
                    "sesskey": Config.sesskey,
                    "phone": phone,
                    "country": mpesaConfig.country,
                    "component": component,
                    "paymentarea": paymentArea,
                    "merchantrequestid": merchantrequestid,
                    "itemid": itemId,
                    "description": description,
                    "reference": mpesaConfig.reference,
                }));
                cancelButton = modal.getRoot().find('#mpesa-cancel');
                cancelButton.on('click', function() {
                    e.preventDefault();
                    modal.destroy();
                });
                payButton = modal.getRoot().find('#mpesa-pay');
                payButton.on('click', function() {
                    modal.destroy();
                });
                payButton.attr('disabled', true);
                var arrayints = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
                var interval = mpesaConfig.timeout;
                var cont = true;
                const b = '</div>';
                arrayints.forEach(function(el, index) {
                    setTimeout(function() {
                        if (cont == true) {
                            var progressDiv = modal.getRoot().find('#mpesa-progress_bar');
                            progressDiv.attr('value', el * 10);
                            if (mpesaPay.xreferenceid != '') {
                                Ajax.call([{
                                    methodname: "paygw_mpesakenya_transaction_complete",
                                    args: {
                                        component,
                                        paymentarea: paymentArea,
                                        itemid: itemId,
                                        merchantrequestid: merchantrequestid,
                                    },
                                    done: function(mpesaPing) {
                                        modal.setFooter(el + '/10 ' + mpesaPing.message);
                                        var spinnerDiv = modal.getRoot().find('#mpesa-spinner');
                                        if (mpesaPing.success) {
                                            if (mpesaPing.message == 'SUCCESSFUL') {
                                                cont = false;
                                                progressDiv.attr('value', 100);
                                                spinnerDiv.attr('style', 'display: none;');
                                                var cancelButton = modal.getRoot().find('#mpesa-cancel');
                                                cancelButton.attr('style', 'display: none;');
                                                var payButton = modal.getRoot().find('#mpesa-pay');
                                                payButton.removeAttr('disabled');
                                                modal.setFooter('Transaction '+ merchantrequestid + ' Succes');
                                                payButton.on('click', function() {
                                                    const loc = window.location.href;
                                                    window.location.replace(loc);
                                                });
                                            }
                                            if (mpesaPing.message == 'ERROR') {
                                                cont = false;
                                                const a = '<br/><div class="p-3 mb-2 bg-danger text-white font-weight-bold">';
                                                var outDiv = modal.getRoot().find('#mpesa-out');
                                                outDiv.append(a + mpesaPing.desc + b);
                                                spinnerDiv.attr('style', 'display: none;');
                                                return;

                                            }
                                        } else {
                                            cont = false;
                                            const a = '<br/><div class="p-3 mb-2 bg-danger text-white font-weight-bold">';
                                            var outDiv = modal.getRoot().find('#mpesa-out');
                                            outDiv.append(a + mpesaPing.message + b);
                                            spinnerDiv.attr('style', 'display: none;');
                                            return;
                                        }
                                    },
                                    fail: function(e) {
                                        console.log(getString('failed', 'paygw_mpesakenya'));  // eslint-disable-line
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
            }).catch(function(res) {
                console.log(res);
                modal.setBody(getString('unable', 'paygw_mpesakenya'));
                console.log('Unable to connect to MPesa');  // eslint-disable-line
            });
        });
        return new Promise(() => null);
    }).catch(e => {
        Log.debug('Global error.');
        Log.debug(e);
        return Promise.reject(e.message);
    });
};
