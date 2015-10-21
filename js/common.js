/**
 * Get slug for given string
 *
 * @param  {string} str
 * @return {string}
 */
function getSlug(str) {
    return str
        .toLowerCase()
        .replace(/[^a-z0-9]/g, '-')
        .replace(/-+/g, '-')
        .replace(/-$/, '')
        .replace(/^-/, '');
}

/**
 * php `htmlspecialchars` equivalent, escapes HTML special characters
 *
 * @param  {string} text String to be escaped
 * @return {string}      Escaped string
 */
function escapeHtml(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}


/**
 * unescapes HTML special characters escaped by `escapeHtml`
 *
 * @param  {string} text String to be unescaped
 * @return {string}      Unescaped string
 */
function unescapeHtml(text) {
  return text
      .replace(/&amp;/g, "&")
      .replace(/&lt;/g, "<")
      .replace(/&gt;/g, ">")
      .replace(/&quot;/g, "\"")
      .replace(/&#039;/g, "'");
}
