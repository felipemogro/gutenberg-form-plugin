import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, PanelRow, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

registerBlockType('gfp/form-block', {
    title: __('Formulario de Sugerencias', 'gfp-plugin-textdomain'),
    icon: 'feedback',
    category: 'widgets',
    attributes: {
        bgColor: {
            type: 'string',
            default: 'lightcoral',
        },
    },
    edit: ({ attributes, setAttributes }) => {
        const { bgColor } = attributes;

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Configuración de Color de Fondo', 'gfp-plugin-textdomain')}>
                        <PanelRow>
                            <SelectControl
                                label={__('Color de Fondo', 'gfp-plugin-textdomain')}
                                value={bgColor}
                                options={[
                                    { label: __('Azul claro', 'gfp-plugin-textdomain'), value: 'lightblue' },
                                    { label: __('Verde claro', 'gfp-plugin-textdomain'), value: 'lightgreen' },
                                    { label: __('Rojo claro', 'gfp-plugin-textdomain'), value: 'lightcoral' },
                                ]}
                                onChange={(value) => setAttributes({ bgColor: value })}
                            />
                        </PanelRow>
                    </PanelBody>
                </InspectorControls>
                <div style={{ backgroundColor: bgColor, padding: '20px', borderRadius: '10px' }}>
                    <p>{__('Este es el formulario de sugerencias.', 'gfp-plugin-textdomain')}</p>
                </div>
            </>
        );
    },
    save: ({ attributes }) => {
        const { bgColor } = attributes;
        return (
            <div style={{ backgroundColor: bgColor, padding: '20px', borderRadius: '10px' }}>
                <form id="gfp-form" class="form-inline">
                    <div class="row mb-3">
                        <div class="col-md-6 input-group">
                            <span class="input-group-text">{__('Nombre:', 'gfp-plugin-textdomain')}</span>
                            <input type="text" class="form-control" id="gfp-name" name="name" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 input-group">
                            <span class="input-group-text">{__('Apellido:', 'gfp-plugin-textdomain')}</span>
                            <input type="text" class="form-control" id="gfp-lastname" name="lastname" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 input-group">
                            <span class="input-group-text">{__('Email:', 'gfp-plugin-textdomain')}</span>
                            <input type="email" class="form-control" id="gfp-email" name="email" required />
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 input-group">
                            <span class="input-group-text">{__('País:', 'gfp-plugin-textdomain')}</span>
                            <select id="gfp-country" class="form-control" name="country" required></select>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6 input-group">
                            <span class="input-group-text">{__('Sugerencias:', 'gfp-plugin-textdomain')}</span>
                            <textarea id="gfp-suggestions" class="form-control" name="suggestions" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary w-100">{__('Enviar', 'gfp-plugin-textdomain')}</button>
                        </div>
                    </div>
                </form>
                <div id="gfp-form-response" class="mt-3"></div>
            </div>
        );
    },
});
