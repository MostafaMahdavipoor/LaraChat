<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8" />
    <title>Chat</title>
    <link rel="stylesheet" href="{{ asset('messenger/resources/css/style/style_chat.css') }}" />
    <link rel="stylesheet" href="{{ asset('messenger/resources/css/style/style_chat_folders.css') }}" />
    <link rel="stylesheet" href="{{ asset('messenger/resources/css/style/style_chat_chatlist.css') }}" />
    <link rel="stylesheet" href="{{ asset('messenger/resources/css/style/style_chat_dialog.css') }}" />
    <link rel="stylesheet" href="{{ asset('messenger/resources/css/style/style_icons_and_fonts.css') }}" />
    <link rel="stylesheet" href="{{ asset('messenger/resources/css/style/style_add_contacts.css') }}" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</head>

<body onload="refreshChatlist()">
    <section id="Messenger" class="Messenger">
        <div class="folders" id="folders">
            <span onclick="ChatList()" class="folders__menu menu"></span>
            <span onclick="foldersISactive('all')" class="folders__allchat folders--is--active" data-badge="+99">همه پیام ها</span>
            <span onclick="foldersISactive('group')" class="folders__group">گروه ها</span>
            <span onclick="foldersISactive('channel')" class="folders__channel">کانال ها</span>
            <span onclick="foldersISactive('pv')" class="folders__pv">گفتگو ها</span>
            <span onclick="foldersISactive('setting')" class="folders__setting">تنظیمات</span>
            <span class="folders__screenmode folders__light">حالت روشنایی</span>
        </div>

        <div id="chatlist" class="chatlist chatlist__Open">
            <div class="chatlist__icon--sticky">
                <div class="chatlist__icon">
                    <span class="chatlist__title">Messenger</span>
                    <span onclick="addContact()" class="chatlist__addcontact"></span>
                    <span onclick="refreshChatlist()" id="refreshIcon" class="chatlist__refresh"></span>
                </div>
                <div class="search__box">
                    <input type="search" name="search_input" id="search_input" class="search__input" placeholder="search" />
                </div>
            </div>

            <div id="Contacts"></div>
        </div>

        <div id="dialog" class="dialog" style="display:none;">
            <div class="dialog__wrapper">
                <div class="dialog__header">
                    <div class="dialog__header-right">
                    <img
                         src="{{ asset('asset/image/user.png') }}"
                         alt="profile"
                        class="message__img"
                    />

                        <div class="dialog__user">
                            <div id="dialog__name" class="dialog__name">name</div>
                            <span class="dialog__status" data-sub="1000Sub .">Online</span>
                        </div>
                    </div>
                    <div class="dialog__header-left">
                        <span class="dialog__showprofile dialog__header-left--is--active"></span>
                        <span class="dialog__edit"></span>
                        <span class="dialog__search"></span>
                        <span class="dialog__refresh"></span>
                        <!-- <span class="dialog__options"></span> -->
                    </div>
                </div>

                <div id="dialogBody" class="dialog__body">
                    <div class="message message__other">
                        <div class="message__photo">
                            <img src="../image/user.png" alt="profile" class="message__img" />
                            <!-- <div class="message__datalist">
 <div class="message__datalist--name">name</div>
 <span>09:50</span>
 </div> -->
                        </div>
                        <div class="message__card message__card--other">
                            <span class="message__text"> hi !! Im donya! </span>
                        </div>
                    </div>

                    <div class="message message__self">
                        <div class="message__photo">
                            <img src="../image/user.png" alt="profile" class="message__img" />
                            <!-- <div class="message__datalist">
 <div class="message__datalist--name">name</div>
 <span>09:50</span>
 </div> -->
                        </div>
                        <div class="message__card message__card--self">
                            <span class="message__text">hey !!</span>
                        </div>
                    </div>
                </div>

                <div id="footerVoice" class="dialog__footer--voice" style="display: none">
                    <div id="saveButton" onclick="stopRecording()" class="dialog__footer--voice-send dialog__send"></div>
                    <div onclick="cancelRecording()" class="dialog__footer--voice-status">
                        لغو
                    </div>
                    <div id="timerVoice" class="dialog__footer--voice-timer">0:0</div>
                </div>

                <div class="emoji__main" id="emojiMain"></div>

                <form id="send_form" id="footer" class="dialog__footer" onkeyup="IconChanger()" onclick="IconChanger()">
                    <div id="dialog__attach" class="dialog__attach dialog__attach--file"></div>
                    <input type="file" name="dialog__input--attach" id="dialog__input--attach" class="dialog__attach--input" />
                    <button id="dialog__icon" class="dialog__voice" type="submit"></button>
                    <div class="dialog__message">
                        <textarea name="dialog__message" id="dialog__message" class="dialog__message--input" placeholder="message..."></textarea>
                    </div>
                    <div class="dialog__tools">
                        <span onclick="EmojiIconActiv()" id="emojiIcon" class="dialog__emoji"></span>
                    </div>
                </form>

                <div id="footerChannels" class="dialog__footer--channels" style="display: none">
                    بی صدا
                </div>
            </div>
        </div>
    </section>

    <section id="addContact" class="section-Contact" style="display: none">
        <button id="closed" class="close-btn"></button>
        <form name="form-contact" id="form-contact" method="post">
            <h3 class="chatlist__title">اضافه کردن مخاطب</h3>
            <div class="main-input">
                <input class="input-add" type="tel" name="phone-Contact" id="phone-Contact" placeholder=" " autofocus required />
                <div class="cut"></div>
                <label for="phone-Contact" class="placeholder">شماره موبایل مخاطب</label>
            </div>
            <div class="main-input">
                <input class="input-add" type="text" name="name-Contact" id="name-Contact" placeholder=" " required />
                <div class="cut cut-long"></div>
                <label for="Contact" class="placeholder">نام مخاطب</label>
            </div>
            <input id="addition" type="button" class="submit-add" value="افزودن مخاطب" />
        </form>
    </section>

    <section id="messengerSettings" class="section-Contact" style="display: none">
        <button id="closed" class="close-btn"></button>
        <form name="form-contact" id="form-contact" method="post">
            <input id="submit" type="button" class="submit-add" value="ثبت تنطیمات" />
        </form>
    </section>

    <script src="https://unpkg.com/picmo@latest/dist/umd/index.js"></script>
    <script src="https://unpkg.com/wavesurfer.js@7"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
     <script src="{{ asset('messenger/resources/js/pageReaction.js') }}"></script>

</body>

</html>
