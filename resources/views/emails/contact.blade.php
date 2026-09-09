<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        ACES Lacrosse Contact Form
    </title>
</head>


<body
    style="
        margin:0;
        padding:0;
        background:#f4f2f6;
        font-family:Arial, Helvetica, sans-serif;
    "
>

<table
    width="100%"
    cellpadding="0"
    cellspacing="0"
    border="0"
    style="
        width:100%;
        background:#f4f2f6;
        padding:40px 15px;
    "
>

    <tr>

        <td align="center">


            <table
                width="600"
                cellpadding="0"
                cellspacing="0"
                border="0"
                style="
                    width:100%;
                    max-width:600px;
                    background:#ffffff;
                    border-radius:10px;
                    overflow:hidden;
                    box-shadow:0 4px 18px rgba(0,0,0,0.08);
                "
            >


                {{-- HEADER --}}
                <tr>

                    <td
                        style="
                            padding:26px 30px;
                            background:#6a0dad;
                        "
                    >

                        <h2
                            style="
                                margin:0;
                                color:#ffffff;
                                font-size:22px;
                                font-weight:700;
                            "
                        >
                            New ACES Lacrosse Inquiry
                        </h2>


                        <p
                            style="
                                margin:6px 0 0;
                                color:#e6d7ef;
                                font-size:13px;
                            "
                        >
                            Website Contact Form
                        </p>

                    </td>

                </tr>


                {{-- INTRO --}}
                <tr>

                    <td
                        style="
                            padding:28px 30px 12px;
                            color:#36303a;
                            font-size:14px;
                            line-height:1.6;
                        "
                    >

                        A new contact form submission has been received
                        from the ACES Lacrosse website.

                    </td>

                </tr>


                {{-- DETAILS --}}
                <tr>

                    <td style="padding:15px 30px 30px;">


                        <table
                            width="100%"
                            cellpadding="0"
                            cellspacing="0"
                            border="0"
                            style="
                                border:1px solid #e8e2ec;
                                border-radius:8px;
                            "
                        >


                            {{-- PLAYER --}}
                            <tr>

                                <td
                                    width="180"
                                    style="
                                        padding:14px 16px;
                                        background:#faf8fb;
                                        border-bottom:1px solid #e8e2ec;
                                        color:#6a0dad;
                                        font-size:13px;
                                        font-weight:bold;
                                    "
                                >
                                    Player Full Name
                                </td>

                                <td
                                    style="
                                        padding:14px 16px;
                                        border-bottom:1px solid #e8e2ec;
                                        color:#322b36;
                                        font-size:14px;
                                    "
                                >
                                    {{ $player_name ?? 'N/A' }}
                                </td>

                            </tr>


                            {{-- GRAD YEAR --}}
                            <tr>

                                <td
                                    style="
                                        padding:14px 16px;
                                        background:#faf8fb;
                                        border-bottom:1px solid #e8e2ec;
                                        color:#6a0dad;
                                        font-size:13px;
                                        font-weight:bold;
                                    "
                                >
                                    Grad Year
                                </td>

                                <td
                                    style="
                                        padding:14px 16px;
                                        border-bottom:1px solid #e8e2ec;
                                        color:#322b36;
                                        font-size:14px;
                                    "
                                >
                                    {{ $grad_year ?? 'N/A' }}
                                </td>

                            </tr>


                            {{-- PARENT --}}
                            <tr>

                                <td
                                    style="
                                        padding:14px 16px;
                                        background:#faf8fb;
                                        border-bottom:1px solid #e8e2ec;
                                        color:#6a0dad;
                                        font-size:13px;
                                        font-weight:bold;
                                    "
                                >
                                    Parent Name
                                </td>

                                <td
                                    style="
                                        padding:14px 16px;
                                        border-bottom:1px solid #e8e2ec;
                                        color:#322b36;
                                        font-size:14px;
                                    "
                                >
                                    {{ $parent_name ?? 'N/A' }}
                                </td>

                            </tr>


                            {{-- EMAIL --}}
                            <tr>

                                <td
                                    style="
                                        padding:14px 16px;
                                        background:#faf8fb;
                                        border-bottom:1px solid #e8e2ec;
                                        color:#6a0dad;
                                        font-size:13px;
                                        font-weight:bold;
                                    "
                                >
                                    Email
                                </td>

                                <td
                                    style="
                                        padding:14px 16px;
                                        border-bottom:1px solid #e8e2ec;
                                        font-size:14px;
                                    "
                                >

                                    @if(!empty($email))

                                        <a
                                            href="mailto:{{ $email }}"
                                            style="
                                                color:#6a0dad;
                                                text-decoration:none;
                                            "
                                        >
                                            {{ $email }}
                                        </a>

                                    @else

                                        N/A

                                    @endif

                                </td>

                            </tr>


                            {{-- PHONE --}}
                            <tr>

                                <td
                                    style="
                                        padding:14px 16px;
                                        background:#faf8fb;
                                        border-bottom:1px solid #e8e2ec;
                                        color:#6a0dad;
                                        font-size:13px;
                                        font-weight:bold;
                                    "
                                >
                                    Phone Number
                                </td>

                                <td
                                    style="
                                        padding:14px 16px;
                                        border-bottom:1px solid #e8e2ec;
                                        font-size:14px;
                                    "
                                >

                                    @if(!empty($phone))

                                        <a
                                            href="tel:{{ $phone }}"
                                            style="
                                                color:#6a0dad;
                                                text-decoration:none;
                                            "
                                        >
                                            {{ $phone }}
                                        </a>

                                    @else

                                        N/A

                                    @endif

                                </td>

                            </tr>


                            {{-- SUBJECT --}}
                            <tr>

                                <td
                                    style="
                                        padding:14px 16px;
                                        background:#faf8fb;
                                        color:#6a0dad;
                                        font-size:13px;
                                        font-weight:bold;
                                    "
                                >
                                    Subject
                                </td>

                                <td
                                    style="
                                        padding:14px 16px;
                                        color:#322b36;
                                        font-size:14px;
                                    "
                                >
                                    {{ $subject ?? 'N/A' }}
                                </td>

                            </tr>

                        </table>

                    </td>

                </tr>


                {{-- MESSAGE --}}
                <tr>

                    <td
                        style="
                            padding:0 30px 30px;
                        "
                    >

                        <div
                            style="
                                margin-bottom:9px;
                                color:#6a0dad;
                                font-size:13px;
                                font-weight:bold;
                            "
                        >
                            Message
                        </div>


                        <div
                            style="
                                padding:18px;
                                background:#faf8fb;
                                border:1px solid #e8e2ec;
                                border-left:4px solid #6a0dad;
                                border-radius:6px;
                                color:#322b36;
                                font-size:14px;
                                line-height:1.7;
                            "
                        >
                            {!! nl2br(e($user_message ?? 'N/A')) !!}
                        </div>

                    </td>

                </tr>


                {{-- REPLY BUTTON --}}
                @if(!empty($email))

                    <tr>

                        <td
                            align="center"
                            style="
                                padding:0 30px 35px;
                            "
                        >

                            <a
                                href="mailto:{{ $email }}"
                                style="
                                    display:inline-block;
                                    padding:13px 28px;
                                    background:#6a0dad;
                                    color:#ffffff;
                                    text-decoration:none;
                                    border-radius:6px;
                                    font-size:14px;
                                    font-weight:bold;
                                "
                            >
                                Reply to Parent
                            </a>

                        </td>

                    </tr>

                @endif


                {{-- FOOTER --}}
                <tr>

                    <td
                        style="
                            padding:17px 30px;
                            background:#faf8fb;
                            border-top:1px solid #ebe6ee;
                            color:#96909a;
                            font-size:11px;
                            line-height:1.6;
                            text-align:center;
                        "
                    >

                        This email was automatically generated from
                        the ACES Lacrosse website contact form.

                    </td>

                </tr>


            </table>

        </td>

    </tr>

</table>

</body>

</html>
