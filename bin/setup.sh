#!/usr/bin/env bash

# 実行ファイルのディレクトリへ移動
cd `dirname $0`

# ======================================================================
# 定数定義
#
readonly FILE_NAME=`basename $0`
readonly INPUT_MESSAGE="を入力してください >> "
readonly TOOLS_BUILD_ASSETS_PATH="bin/build_assets.sh"
readonly PHP_GENERATE_DB_CONF_PATH="bin/generate_db_conf.php"
readonly CRON_DELETE_OLD_DATA_TEMPLATE_PATH="docs/cron/delete_old_data.tmp"
readonly CRON_UPDATE_FC2_BLOG_ENTRIES_TEMPLATE_PATH="docs/cron/update_fc2_blog_entries.tmp"
readonly CRON_ALL_JOBS_TEMPORARY_PATH="/tmp/__${FILE_NAME}__tmp__"

#
# ======================================================================


# ======================================================================
# 関数定義
#
function prompt_overview() {
cat << EOT
------------------------------------------------------------------------
${FILE_NAME} は入力内容に基づいて以下設定を行いシステムを利用可能な状態にします。
1. WEB 設定
- 公開ディレクトリにファイルを配備します。

2. MySQL 設定
システムが利用するデータベースの情報を設定します。

3. CRON JOBS 追加

------------------------------------------------------------------------
EOT
}

function display_success() {
cat << EOT
設定は完了しました。
EOT
exit 0;
}

function display_error() {
cat << EOT
設定は中断されました。
EOT
exit 1;
}

function read_yesno() {
  printf "セットアップを開始します。 (Y/n) >> "
  read answer
  answer=`echo ${answer} | grep -iE '^(yes|y|no|n)$' | tr "[:lower:]" "[:upper:]"`
  case "$answer" in
    "Y" | "YES") return 0;;
    "N" | "NO") return 1;;
    *) read_yesno;;
  esac
}

function setup_web() {
  echo "1. WEB 設定"
  printf "公開ディレクトリを絶対パスで${INPUT_MESSAGE}"
  read -r root_dir

  if [ ! -n "$root_dir" ]; then
    setup_web
  elif [ ! -d "$root_dir" ]; then
    echo "${root_dir} はディレクトリではありません。ディレクトリを入力してください。"
    setup_web
  else
    cd ".."
    cp -a ./* "${root_dir}"
    echo "公開ディレクトリにファイルを配備しました。"

    cd ${root_dir}
    /bin/bash "${TOOLS_BUILD_ASSETS_PATH}"
    echo -e "bin/build_assets.sh を実行しました。\n"

  fi
}

function setup_db() {
  echo "2. MySQL 設定"
  printf "HOST ${INPUT_MESSAGE}"
  read host
  printf "DB NAME ${INPUT_MESSAGE}"
  read db_name
  printf "USER ${INPUT_MESSAGE}"
  read USER
  printf "PASSWORD ${INPUT_MESSAGE}"
  read PW

  if [ ! -n "$host" -o ! -n "$db_name" -o ! -n "$USER" -o ! -n "$PW" ]; then
    echo "未入力の値があります。再度入力してください。"
    setup_db
  else
    `echo php "${root_dir}"/"${PHP_GENERATE_DB_CONF_PATH}" "${host}" "${db_name}" "${USER}" "${PW}"` && echo -e "${root_dir}/${PHP_GENERATE_DB_CONF_PATH} を実行しました。\n"
  fi
}

function setup_cron_jobs() {
  echo "3. CRON JOBS 追加"

  # 現在の 実行ユーザーの crontab 一覧を取得
  local current_cron_jobs=`crontab -l`

  # setup.sh のコメントがある場合は実行しない
  if [[ "${current_cron_jobs}" =~ setup\.sh ]]; then
    echo -e "CRON JOBS に設定済み。\n"
  else
    # ファイルを読み込む
    local cron_delete_old_data_template=`cat ${CRON_DELETE_OLD_DATA_TEMPLATE_PATH}`
    cron_delete_old_data_template=${cron_delete_old_data_template/__DIR__/${root_dir}}

    local cron_update_fc2_blog_entries_template=`cat ${CRON_UPDATE_FC2_BLOG_ENTRIES_TEMPLATE_PATH}`
    cron_update_fc2_blog_entries_template=${cron_update_fc2_blog_entries_template/__DIR__/${root_dir}}

    local readonly CURRENT_DATE=`date`
    current_cron_jobs="${current_cron_jobs}\n####### ADDED BY ${FILE_NAME} on ${CURRENT_DATE} #######"
    current_cron_jobs="${current_cron_jobs}\n${cron_delete_old_data_template}"
    current_cron_jobs="${current_cron_jobs}\n${cron_update_fc2_blog_entries_template}"
    current_cron_jobs="${current_cron_jobs}\n####### ADDED BY ${FILE_NAME} on ${CURRENT_DATE} #######\n"

    `echo -e "${current_cron_jobs}" > "${CRON_ALL_JOBS_TEMPORARY_PATH}"`

    `crontab -r`
    `crontab "${CRON_ALL_JOBS_TEMPORARY_PATH}"`
    `rm "${CRON_ALL_JOBS_TEMPORARY_PATH}"`

    echo -e "CRON JOBS に設定を書き込みました。\n"
  fi

}
#
# ======================================================================


# ======================================================================
# メイン処理
#
prompt_overview
read_yesno

# yes の場合
if [ $? = 0 ]; then
  setup_web
  setup_db
  setup_cron_jobs
  display_success
else # no の場合
  display_error
fi
#
# ======================================================================
