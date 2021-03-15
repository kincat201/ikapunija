<div style="margin:0;padding:0" dir="ltr" bgcolor="#ffffff">
  <table border="0" cellspacing="0" cellpadding="0" align="center" id="m_2538005969184539696m_4110794995062804931email_table" style="border-collapse:collapse">
    <tbody>
      <tr>
        <td id="m_2538005969184539696m_4110794995062804931email_content" style="font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;background:#ffffff">
          <table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
            <tbody>
              <tr>
                <td height="20" style="line-height:20px" colspan="3">&nbsp;</td>
              </tr>
              <tr>
                @if($data['subject'] == 'Verifikasi Akun')
                  <td height="1" colspan="3" style="line-height:1px"><span style="color:#ffffff;font-size:1px">&nbsp; Halo {{ $data['name'] }}, Anda dapat masuk ke ke Halaman Berikut untuk login ke Webiste Ikapunija, Jika Anda tidak mencoba masuk, beri tahu kami . &nbsp;</span></td>
                @elseif($data['subject'] == 'Decline Akun' || $data['subject'] == 'Approval Akun')
                  <td height="1" colspan="3" style="line-height:1px"><span style="color:#ffffff;font-size:1px"> &nbsp;</span></td>
                @else
                  <td height="1" colspan="3" style="line-height:1px"><span style="color:#ffffff;font-size:1px">&nbsp; Halo {{ $data['name'] }}, Anda dapat memasukkan <span>kode</span> ini untuk <b>Reset Password</b> pada Webiste Ikapunija: {{ $data['code'] }}, Jika memang bukan anda, mohon dihiraukan. &nbsp;</span></td>
                @endif
              </tr>
              <tr>
                <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                <td>
                  <table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
                    <tbody>
                      <tr>
                        <td height="15" style="line-height:15px" colspan="3">&nbsp;</td>
                      </tr>
                      <tr>
                        <td width="32" align="left" valign="middle" style="height:32;line-height:0px"><img src="https://admin.ikapunija.com/asset_image/email/logo.jpeg" width="32" height="32" style="border:0" class="CToWUd"></td>
                        <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                        <td width="100%"><span style="font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;font-size:19px;line-height:32px;color:#3b5998">Webiste Ikapunija</span></td>
                      </tr>
                      <tr style="border-bottom:solid 1px #e5e5e5">
                        <td height="15" style="line-height:15px" colspan="3">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
              </tr>
              <tr>
                <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                <td>
                  <table border="0" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
                    <tbody>
                      <tr>
                        <td height="28" style="line-height:28px">&nbsp;</td>
                      </tr>
                      <tr>
                        <td>
                          <span style="font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;font-size:16px;line-height:21px;color:#141823">
                            Halo {{ $data['name'] }},
                            @if($data['subject'] == 'Verifikasi Akun')
                              <p>Anda dapat masuk ke ke <a href="https://admin.ikapunija.com/api/verifikasi_akun/{{ $data['email'] }}/{{ $data['code'] }}" target="_blank">Halaman Berikut<a> untuk login ke Webiste Ikapunija</p>
                            @elseif($data['subject'] == 'Approval Akun')
                              <p>Selamat Akun Anda Berhasil Terdaftar, Silahkan login pada link berikut : <a href="https://ikapunija.com/login" target="_blank">https://ikapunija.com/login<a> untuk melakukan login ke Webiste Ikapunija</p>
                            @elseif($data['subject'] == 'Decline Akun')
                              <p>Maaf Akun Anda Gagal dalam Pendaftaran</p>
                            @else
                              <p>Anda dapat memasukkan <span>kode</span> ini {{ $data['perihal'] }}:</p>
                              <table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse">
                                <tbody>
                                  <tr>
                                    <td style="font-size:11px;font-family:LucidaGrande,tahoma,verdana,arial,sans-serif;padding:10px;background-color:#f2f2f2;border-left:1px solid #ccc;border-right:1px solid #ccc;border-top:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;font-size:16px;line-height:21px;color:#141823">{{ $data['code'] }}</span></td>
                                  </tr>
                                </tbody>
                              </table>
                            @endif
                            
                            <p></p>
                            Jika ada kendala, anda bisa menghubungi
                            <a href="mailto:cs@ikapunija.com">cs@ikapunija.com</a>
                            .
                            @if($data['subject'] == 'Verifikasi Akun')
                            <p>Jika memang bukan Anda yang melakukan ini mohon dihiraukan, Terima Kasih !</p>
                            @endif
                          </span>
                        </td>
                      </tr>
                      <tr>
                        <td height="28" style="line-height:28px">&nbsp;</td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
              </tr>
              <tr>
                <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
                <td>
                  <table border="0" width="100%" cellspacing="0" cellpadding="0" align="left" style="border-collapse:collapse">
                    <tbody>
                      <tr style="border-top:solid 1px #e5e5e5">
                        <td height="19" style="line-height:19px">&nbsp;</td>
                      </tr>
                      <tr>
                        <td style="font-family:Helvetica Neue,Helvetica,Lucida Grande,tahoma,verdana,arial,sans-serif;font-size:11px;color:#aaaaaa;line-height:16px">Pesan ini dikirim ke <a href="mailto:{{ $data['email'] }}" style="color:#3b5998;text-decoration:none" target="_blank">{{ $data['email'] }}</a>.<br>Website Ikapunija</td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td width="15" style="display:block;width:15px">&nbsp;&nbsp;&nbsp;</td>
              </tr>
              <tr>
                <td height="20" style="line-height:20px" colspan="3">&nbsp;</td>
              </tr>
            </tbody>
          </table>
        </td>
      </tr>
    </tbody>
  </table>
</div>