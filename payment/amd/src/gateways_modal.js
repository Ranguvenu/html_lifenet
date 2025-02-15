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
 * Contain the logic for the gateways modal.
 *
 * @module     core_payment/gateways_modal
 * @copyright  2019 Shamim Rezaie <shamim@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
import $ from 'jquery';
import Templates from 'core/templates';
import {getString} from 'core/str';
import {getAvailableGateways} from './repository';
import Selectors from './selectors';
import ModalEvents from 'core/modal_events';
import PaymentEvents from 'core_payment/events';
import {add as addToast, addToastRegion} from 'core/toast';
import Notification from 'core/notification';
import ModalGateways from './modal_gateways';

/**
 * Register event listeners for the module.
 */
const registerEventListeners = () => {
    document.addEventListener('click', e => {
        const gatewayTrigger = e.target.closest('[data-action="core_payment/triggerPayment"]');
        if (gatewayTrigger) {
            e.preventDefault();

            show(gatewayTrigger, {focusOnClose: e.target});
        }
    });
};

/**
 * Shows the gateway selector modal.
 *
 * @param {HTMLElement} rootNode
 * @param {Object} options - Additional options
 * @param {HTMLElement} options.focusOnClose The element to focus on when the modal is closed.
 */
const show = async(rootNode, {
    focusOnClose = null,
} = {}) => {

    // Load upfront, so we don't try to inject the internal content into a possibly-not-yet-resolved promise.
    const body = await Templates.render('core_payment/gateways_modal', {});

    const modal = await ModalGateways.create({
        title: getString('selectpaymenttype', 'core_payment'),
        body: body,
        show: true,
        removeOnClose: true,
    });

    const rootElement = modal.getRoot()[0];
    addToastRegion(rootElement);

    modal.getRoot().on(ModalEvents.hidden, () => {
        focusOnClose?.focus();
    });

    modal.getRoot().on(PaymentEvents.proceed, async(e) => {
        e.preventDefault();
        const gateway = (rootElement.querySelector(Selectors.values.gateway) || {value: ''}).value;

        if (gateway) {
            processPayment(
                gateway,
                rootNode.dataset.component,
                rootNode.dataset.paymentarea,
                rootNode.dataset.itemid,
                rootNode.dataset.description
            ).then((message) => {
                modal.hide();
                Notification.addNotification({
                    message,
                    type: 'success',
                });
                location.href = rootNode.dataset.successurl;

                return;
            }).catch(message => Notification.alert('', message));
        } else {
            // We cannot use await in the following line.
            // The reason is that we are preventing the default action of the save event being triggered,
            // therefore we cannot define the event handler function asynchronous.
            addToast(getString('nogatewayselected', 'core_payment'), {
                type: 'warning',
            });
        }
    });

    // Re-calculate the cost when gateway is changed.
    rootElement.addEventListener('change', e => {
        if (e.target.matches(Selectors.elements.gateways)) {
            updateCostRegion(rootElement, rootNode.dataset.cost);
        }
    });

    const gateways = await getAvailableGateways(rootNode.dataset.component, rootNode.dataset.paymentarea, rootNode.dataset.itemid);
    const context = {
        gateways
    };

    const {html, js} = await Templates.renderForPromise('core_payment/gateways', context);
    Templates.replaceNodeContents(rootElement.querySelector(Selectors.regions.gatewaysContainer), html, js);
    selectSingleGateway(rootElement);
    await updateCostRegion(rootElement, rootNode.dataset.cost);
};

/**
 * Auto-select the gateway if there is only one gateway.
 *
 * @param {HTMLElement} root An HTMLElement that contains the cost region
 */
const selectSingleGateway = root => {
    const gateways = root.querySelectorAll(Selectors.elements.gateways);

    if (gateways.length == 1) {
        gateways[0].checked = true;
    }
};

/**
 * Shows the cost of the item the user is purchasing in the cost region.
 *
 * @param {HTMLElement} root An HTMLElement that contains the cost region
 * @param {string} defaultCost The default cost that is going to be displayed if no gateway is selected
 * @returns {Promise<void>}
 */
const updateCostRegion = async(root, defaultCost = '') => {
    const gatewayElement = root.querySelector(Selectors.values.gateway);
    const surcharge = parseInt((gatewayElement || {dataset: {surcharge: 0}}).dataset.surcharge);
    const cost = (gatewayElement || {dataset: {cost: defaultCost}}).dataset.cost;
    const valueStr = surcharge ? await getString('feeincludesurcharge', 'core_payment', {fee: cost, surcharge: surcharge}) : cost;

    const surchargeStr = await getString('labelvalue', 'core',
        {
            label: await getString('cost', 'core'),
            value: valueStr
        }
    );

    const {html, js} = await Templates.renderForPromise('core_payment/fee_breakdown', {surchargestr: surchargeStr});
    Templates.replaceNodeContents(root.querySelector(Selectors.regions.costContainer), html, js);
};

/**
 * Process payment using the selected gateway.
 *
 * @param {string} gateway The gateway to be used for payment
 * @param {string} component Name of the component that the itemId belongs to
 * @param {string} paymentArea Name of the area in the component that the itemId belongs to
 * @param {number} itemId An internal identifier that is used by the component
 * @param {string} description Description of the payment
 * @returns {Promise<string>}
 */
const processPayment = async(gateway, component, paymentArea, itemId, description) => {
    const paymentMethod = await import(`paygw_${gateway}/gateways_modal`);
    return paymentMethod.process(component, paymentArea, itemId, description);
};
/**
 * Checking the radio for showing the input text field.
 *
 * @param {Event} e event
 */
export const checkRadio = (e) => {
    var name = e.target.getAttribute('name');
    var radioval = $("input[name='"+name+"']:checked").val();
    if (radioval == 'other') {
        $("input[name='mobilenumber']").css('display','block');
    } else {
        $("input[name='mobilenumber']").css('display','none');
    }
};
/**
 * Set up the payment actions.
 */
export const init = () => {
    if (!init.initialised) {
        // Event listeners should only be registered once.
        init.initialised = true;
        registerEventListeners();
    }
};

/**
 * Whether the init function was called before.
 *
 * @static
 * @type {boolean}
 */
init.initialised = false;
