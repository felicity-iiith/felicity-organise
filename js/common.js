/**
 * Get slug for given string
 *
 * @param {string} str
 */
function getSlug(str) {
    return str
        .toLowerCase()
        .replace(/[^a-z0-9]/g, '-')
        .replace(/-+/g, '-');
}
