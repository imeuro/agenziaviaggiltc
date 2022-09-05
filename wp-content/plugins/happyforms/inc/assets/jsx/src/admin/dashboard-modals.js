import DashboardModals from '@happyforms/core/jsx/src/admin/dashboard-modals';
import { SlotFillProvider, Button, Modal, Guide, Popover, Notice, ExternalLink, TextControl, CheckboxControl, BaseControl } from '@wordpress/components';
import { useState, useReducer, useRef } from '@wordpress/element';
import { __, sprintf } from '@wordpress/i18n';

( function( $, settings ) {

	/**
	 *
	 * Onboarding modal
	 *
	 */
	const OnboardingModal = ( props ) => {
		const imageURL = `${settings.pluginURL}/inc/assets/img/welcome.svg`;
		const [ email, setEmail ] = useState( '' );

		const onEmailChange = ( e ) => {
			setEmail( e.target.value );
		};

		const onRequestClose = () => {
			$.post( ajaxurl, {
				action: settings.onboardingModalAction,
				_wpnonce: settings.onboardingModalNonce,
				email: email,
			} );

			return props.onRequestClose();
		}

		return(
			<Guide
				onFinish={ onRequestClose }
				className="happyforms-modal happyforms-modal--onboarding"
				pages={ [
					{
						image: (
							<picture>
								<img src={imageURL} />
							</picture>
						),
						content: (
							<>
							<div className="happyforms-modal__header">
								<h1>{ __( 'One last thing', 'happyforms' ) }</h1>
								<p>{ __( 'We\'ll occasionally send you emails about plugin updates. And don\'t sweat it, you can unsubscribe anytime.', 'happyforms' ) }</p>
							</div>
							<div className="happyforms-modal__body">
								<label>{ __( 'Email address', 'happyforms' ) }</label>
								<input type="email" value={ email } onChange={ onEmailChange } autoFocus />
							</div>
							<div className="happyforms-modal__footer">
								<BaseControl>
									<Button isPrimary={true} onClick={ onRequestClose } text={ __( 'Continue', 'happyforms' ) }></Button>
								</BaseControl>
							</div>
							</>
						),
					},
				] }
			/>
		);
	}

	/**
	 *
	 * Upgrade modal
	 *
	 */
	const UpgradeModal = ( props ) => {
		const imageURL = `${settings.pluginURL}/inc/assets/img/upgrade.svg`;

		return(
			<Guide
				onFinish={ props.onRequestClose }
				className="happyforms-modal happyforms-modal--upgrade"
				pages={ [
					{
						image: (
							<picture>
								<img src={imageURL} />
							</picture>
						),
						content: (
							<>
							<div className="happyforms-modal__header">
								<h1>{ __( 'Start with a free trial', 'happyforms' ) }</h1>
							</div>
							<div className="happyforms-modal__body">
								<p>{ __( 'You\'re just a mouse click and a few key taps away from building better forms.', 'happyforms' ) }</p>
								<ul>
									<li>{ __( 'Advanced features and integrations', 'happyforms' ) }</li>
									<li>{ __( 'Help from our friendly support team', 'happyforms' ) }</li>
									<li>{ __( 'Automatically transfer your free forms', 'happyforms' ) }</li>
									<li>{ __( 'New updates every second week', 'happyforms' ) }</li>
								</ul>
								<p>{ __( 'Ready to build better forms?', 'happyforms' ) }</p>
							</div>
							<div className="happyforms-modal__footer">
								<BaseControl>
									<Button isPrimary={true} href="https://happyforms.io/upgrade" target="_blank" text={ __( 'Start a Free 7-day Trial', 'happyforms' ) }></Button>
									<Button isSecondary={true} onClick={ props.onRequestClose } text={ __( 'Nope, Maybe Later', 'happyforms' ) }></Button>
								</BaseControl>
							</div>
							</>
						),
					},
				] }
			/>
		);
	}

	const DashboardModalsBaseClass = DashboardModals( $, settings );

	class DashboardModalsClass extends DashboardModalsBaseClass {

		openOnboardingModal() {
			var modal = (
				<OnboardingModal
					onRequestClose={ this.closeModal.bind( this, 'onboarding' ) }
					status={ settings.trackingStatus } />
			);

			this.openModal( modal );
		}

		openUpgradeModal() {
			var modal = <UpgradeModal onRequestClose={ this.closeModal.bind( this, 'upgrade' ) } />

			this.openModal( modal );
		}

	};

	var happyForms = window.happyForms || {};
	window.happyForms = happyForms;

	happyForms.modals = new DashboardModalsClass();

} )( jQuery, _happyFormsDashboardModalsSettings );
