import 'ol/ol.css';
import Map from 'ol/Map';
import View from 'ol/View';
import {Fill, Stroke, Style} from 'ol/style';
import {Draw, Modify, Snap} from 'ol/interaction';
import {OSM, Vector as VectorSource} from 'ol/source';
import {Tile as TileLayer, Vector as VectorLayer} from 'ol/layer';

const source = new VectorSource();
const vector = new VectorLayer({
    source: source,
    style: new Style({
        fill: new Fill({
            color: 'rgba(255, 255, 255, 0.2)',
        }),
        stroke: new Stroke({
            color: '#ffcc33',
            width: 2,
        }),
    }),
});

const map = new Map({
    layers: [
        new TileLayer({
            source: new OSM(),
        }),
        vector
    ],
    target: 'map',
    view: new View({
        center: [0, 0],
        zoom: 2,
    }),
});

const modify = new Modify({source: source});
map.addInteraction(modify);
const draw = new Draw({
    source: source,
    type: "LineString",
});
map.addInteraction(draw);
const snap = new Snap({source: source});
map.addInteraction(snap);

