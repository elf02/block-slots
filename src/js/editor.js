import { registerBlockVariation } from '@wordpress/blocks';
import { withQueryLoopControl } from './control-query-loop';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';

const queryProps = {
    postType: 'block_slot',
    include: [0],
    perPage: 1,
    pages: 0,
    offset: 0,
    order: 'desc',
    orderBy: 'date',
    author: '',
    search: '',
    exclude: [],
    sticky: '',
    inherit: false,
    parents: [],
};

const innerBlocks = [
    [
        'core/post-template',
        {},
        [
            [
                'core/post-content',
                {},
                [],
            ]
        ],
    ]
];

registerBlockVariation('core/query', {
    name: 'elf02/block-slots-query-loop',
    title: __('Block-Slots Query Loop', 'elf02-block-slots'),
    attributes: {
        namespace: 'elf02/block-slots-query-loop',
        className: 'block-slot',
        align: 'full',
        query: queryProps
    },
    allowedControls: [],
    innerBlocks: innerBlocks,
    isActive: ['namespace']
});

addFilter('editor.BlockEdit', 'elf02/block-slots-query-loop-edit', withQueryLoopControl);