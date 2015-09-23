var bracketSource = null;
var bracket = null;

function initBracketEditor(compoId) {
    bracketSource = new DataSource(compoId);
    bracket = bracketSource.derive("editor-canvas", '.*');
}
