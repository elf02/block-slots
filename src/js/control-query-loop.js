import { __ } from '@wordpress/i18n';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, SelectControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';


export const withQueryLoopControl = (BlockEdit) => (props) => {
    const { attributes, setAttributes } = props;

    if (props.attributes?.namespace === 'elf02/block-slots-query-loop') {
        const { query } = attributes;

        const blockSlots = useSelect((select) => {
            return select('core').getEntityRecords('postType', 'block_slot', { per_page: -1, status: 'publish' });
        });

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Block Slot', 'elf02-block-slots')}>
                        <SelectControl
                            value={query?.include[0] || 0}
                            options={blockSlots ? [
                                { label: __('Select...', 'elf02-block-slots'), value: 0 },
                                ...blockSlots.map(({ id, title: { rendered: postTitle } }) => {
                                    return { label: postTitle, value: id };
                                })
                            ] : []}
                            onChange={(value) => {
                                setAttributes({
                                    query: {
                                        ...query,
                                        'include': [parseInt(value)]
                                    }
                                });
                            }}
                        />
                    </PanelBody>
                </InspectorControls>
                <BlockEdit {...props} />
            </>
        );
    }

    return <BlockEdit {...props} />
};
