import { registerBlockType } from '@wordpress/blocks';
import { 
  useBlockProps, 
  InspectorControls, 
  ColorPalette 
} from '@wordpress/block-editor';
import { 
  PanelBody, 
  TextControl, 
  RangeControl, 
  ToggleControl 
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';

// Editor styles
import './editor.scss';
// Frontend styles
import './style.scss';

// Edit component
function Edit({ attributes, setAttributes }) {
  const { 
    title, 
    backgroundColor,
    width,
    xOffset,
    yOffset,
    enablePulse,
    pulseIntensity,
    pulseSpeed
  } = attributes;
  
  const blockProps = useBlockProps.save({
    className: `vm-bubble ${enablePulse ? 'pulse-animation' : ''}`,
    style: {
      backgroundColor,
      width: `${width}rem`,
      height: `${width}rem`,
      position: 'absolute',
      left: `${xOffset}rem`,
      top: `${yOffset}rem`,
      '--pulse-intensity': `${pulseIntensity}%`,
      '--pulse-speed': `${pulseSpeed}s`
    }
  });

  return (
    <>
      <InspectorControls>
        <PanelBody title={__('Bubble Settings', 'vm-bubble-blocks')}>
          <TextControl
            label={__('Bubble Title', 'vm-bubble-blocks')}
            value={title}
            onChange={(value) => setAttributes({ title: value })}
          />
          
          <div className="components-base-control">
            <label className="components-base-control__label">
              {__('Background Color', 'vm-bubble-blocks')}
            </label>
            <ColorPalette
              value={backgroundColor}
              onChange={(color) => setAttributes({ backgroundColor: color })}
            />
          </div>
          
          <RangeControl
            label={__('Bubble Size', 'vm-bubble-blocks')}
            value={width}
            onChange={(value) => setAttributes({ width: value })}
            min={10}
            max={50}
          />
          
          <RangeControl
            label={__('X Position Offset', 'vm-bubble-blocks')}
            value={xOffset}
            onChange={(value) => setAttributes({ xOffset: value })}
            min={-100}
            max={100}
          />
          
          <RangeControl
            label={__('Y Position Offset', 'vm-bubble-blocks')}
            value={yOffset}
            onChange={(value) => setAttributes({ yOffset: value })}
            min={-100}
            max={100}
          />
        </PanelBody>
        
        <PanelBody title={__('Animation Settings', 'vm-bubble-blocks')}>
          <ToggleControl
            label={__('Enable Pulse Animation', 'vm-bubble-blocks')}
            checked={enablePulse}
            onChange={(value) => setAttributes({ enablePulse: value })}
          />
          
          {enablePulse && (
            <>
              <RangeControl
                label={__('Pulse Intensity', 'vm-bubble-blocks')}
                value={pulseIntensity}
                onChange={(value) => setAttributes({ pulseIntensity: value })}
                min={1}
                max={100}
              />
              
              <RangeControl
                label={__('Pulse Speed (seconds)', 'vm-bubble-blocks')}
                value={pulseSpeed}
                onChange={(value) => setAttributes({ pulseSpeed: value })}
                min={1}
                max={10}
                step={0.25}
              />
            </>
          )}
        </PanelBody>
      </InspectorControls>
      
      <div {...blockProps} >
        {title && <h4 className="vm-bubble-title">{title}</h4>}
      </div>
    </>
  );
}

// Save component
function save({ attributes }) {
  const { 
    title, 
    backgroundColor,
    width,
    xOffset,
    yOffset,
    enablePulse,
    pulseIntensity,
    pulseSpeed
  } = attributes;
  
  const blockProps = useBlockProps.save({
    className: `vm-bubble ${enablePulse ? 'pulse-animation' : ''}`,
    style: {
      backgroundColor,
      width: `${width}rem`,
      height: `${width}rem`,
      position: 'absolute',
      left: `${xOffset}rem`,
      top: `${yOffset}rem`,
      '--pulse-intensity': `${pulseIntensity}0%`,
      '--pulse-speed': `${pulseSpeed}s`
    }
  });

  return (
    <div {...blockProps}>
      {title && <h4 className="vm-bubble-title">{title}</h4>}
    </div>
  );
}

// Register the block
registerBlockType(metadata.name, {
  edit: Edit,
  save,
});