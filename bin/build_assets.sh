#!/usr/bin/env bash

# ------------------------------------------------------------------------
# public/assets のファイルを1ファイルにまとめて public/build ディレクトリに配備
# JavaScript: public/build/main.js
# CSS: public/build/main.css
# ------------------------------------------------------------------------

echo "[RUN]     `basename $0`"

cd `dirname $0`

mkdir -p ../public/build

# concatenate all js into main.js
readonly DEST_BUILD_JS=../public/build/main.js

`rm -f ${DEST_BUILD_JS}`
`cat ../public/assets/js/conf/settings.js >> ${DEST_BUILD_JS}`
`cat ../public/assets/js/lib/utils.js >> ${DEST_BUILD_JS}`
`cat ../public/assets/js/lib/cookie.js >> ${DEST_BUILD_JS}`
`cat ../public/assets/js/lib/ajax.js >> ${DEST_BUILD_JS}`
`cat ../public/assets/js/lib/Events.js >> ${DEST_BUILD_JS}`
`cat ../public/assets/js/model/BlogModel.js >> ${DEST_BUILD_JS}`
`cat ../public/assets/js/view/view_utils.js >> ${DEST_BUILD_JS}`
`cat ../public/assets/js/view/PagerView.js >> ${DEST_BUILD_JS}`
`cat ../public/assets/js/view/BlogView.js >> ${DEST_BUILD_JS}`
`cat ../public/assets/js/router/BlogRouter.js >> ${DEST_BUILD_JS}`


# concatenate all css into main.css
readonly DEST_BUILD_CSS=../public/build/main.css

`rm -f ${DEST_BUILD_CSS}`
`cat ../public/assets/css/reset.css >> ${DEST_BUILD_CSS}`
`cat ../public/assets/css/common.css >> ${DEST_BUILD_CSS}`
`cat ../public/assets/css/pager.css >> ${DEST_BUILD_CSS}`
`cat ../public/assets/css/blog.css >> ${DEST_BUILD_CSS}`

echo "[SUCCESS]     `basename $0`"
