<?php

use AndreInocenti\SocialMediaUrlIdExtractor\Platforms\FacebookExtractor;
use AndreInocenti\SocialMediaUrlIdExtractor\SocialMediaUrlIdExtractor;
use AndreInocenti\SocialMediaUrlValidator\Enums\PlatformsCategoriesEnum as Cat;

beforeEach(function () {
    $this->extractor = new FacebookExtractor();
});

//
// DATASETS
//

dataset('fb-profile-vanity', [
    'plain'              => ['https://www.facebook.com/Example.Page',                      'Example.Page'],
    'trailing slash'     => ['https://www.facebook.com/Example.Page/',                     'Example.Page'],
    'with query'         => ['https://www.facebook.com/Example.Page/?test=123',            'Example.Page'],
    'subdomain m'        => ['https://m.facebook.com/Example.Page',                        'Example.Page'],
    'subdomain mbasic'   => ['https://mbasic.facebook.com/Example.Page?ref=bookmarks',     'Example.Page'],
    'dots and caps'      => ['https://www.facebook.com/Ex.Ample.Page/',                    'Ex.Ample.Page'],
    'web'                => ['https://web.facebook.com/pagename',                         'pagename'],
    'ptbr'                => ['https://pt-br.facebook.com/pagename/',              'pagename'],
]);

dataset('fb-profile-numeric', [
    'numeric basic'      => ['https://facebook.com/profile.php?id=123456789',              '123456789'],
    'numeric with query' => ['https://www.facebook.com/profile.php?id=123456789&ref=bm',   '123456789'],
    'numeric m'          => ['https://m.facebook.com/profile.php?id=123456789/',           '123456789'],
]);

dataset('fb-posts', [
    // page/user posts (numeric)
    'page posts basic'   => ['https://m.facebook.com/ExamplePage/posts/987654321',         '987654321'],
    'page posts slash'   => ['https://m.facebook.com/ExamplePage/posts/987654321/',        '987654321'],
    'page posts query'   => ['https://www.facebook.com/ExamplePage/posts/987654321?x=1',   '987654321'],
    // page/user posts (pfbid)
    'pfbid post'         => ['https://www.facebook.com/REVISTABOOKINGR/posts/pfbid0AzwwUU8puAowPPuwxg6RqSbj44kFTT3STZsFwJof6DbBbRis79s6kxes13J5HYnWl', 'pfbid0AzwwUU8puAowPPuwxg6RqSbj44kFTT3STZsFwJof6DbBbRis79s6kxes13J5HYnWl'],
    // numeric id on numeric page
    'numeric page post'  => ['https://www.facebook.com/491794399620983/posts/1121416323325451', '1121416323325451'],
    // story.php
    'story basic'        => ['https://facebook.com/story.php?story_fbid=555666777&id=123', '555666777'],
    'story with _rdr'    => ['https://www.facebook.com/story.php?story_fbid=1057786096151501&id=100057603602454&_rdr', '1057786096151501'],
    'story slash query'  => ['https://www.facebook.com/story.php?story_fbid=1057786096151501&id=100057603602454/&x=1', '1057786096151501'],
    // groups
    'group posts'        => ['https://www.facebook.com/groups/123456789012345/posts/987654321098765', '987654321098765'],
    'group permalink'    => ['https://www.facebook.com/groups/123456789012345/permalink/11223344556677/?utm=1', '11223344556677'],
    // pfbid with query/fragment
    'pfbid q frag'       => ['https://www.facebook.com/livealok/posts/pfbid02hZiXMYmzApzCTyPtPdFJcjNoLctb4UjjQ4ZWNRmC1jyWBwpGdAEmnpRQYWZgtftrl?rdid=EDuUBbnFKGvqROQx#', 'pfbid02hZiXMYmzApzCTyPtPdFJcjNoLctb4UjjQ4ZWNRmC1jyWBwpGdAEmnpRQYWZgtftrl'],
]);

dataset('fb-videos', [
    // page videos/
    'page videos'        => ['https://www.facebook.com/PageName/videos/1234567890123456/', '1234567890123456'],
    'page videos query'  => ['https://www.facebook.com/PageName/videos/1234567890123456/?ref=share', '1234567890123456'],
    // video.php?v=
    'video.php basic'    => ['https://m.facebook.com/video.php?v=9876543210',              '9876543210'],
    'video.php query'    => ['https://www.facebook.com/video.php?v=9876543210&set=vb.1',   '9876543210'],
    // watch/?v=
    'watch v'            => ['https://www.facebook.com/watch/?v=123456789012345',          '123456789012345'],
    'watch v noslash'    => ['https://www.facebook.com/watch?v=123456789012345',           '123456789012345'],
    // fb.watch
    'fb.watch'           => ['https://fb.watch/abcDEF123',                                 'abcDEF123'],
    'fb.watch slash'     => ['https://fb.watch/abcDEF123/',                                'abcDEF123'],
    'fb.watch query'     => ['https://fb.watch/abcDEF123?test=123',                        'abcDEF123'],
    // reel (tratado como vídeo)
    'reel'               => ['https://www.facebook.com/reel/1234567890123456/',            '1234567890123456'],
]);

dataset('fb-negatives', [
    // URLs que NÃO deveriam extrair para o tipo pedido
    ['https://www.facebook.com/PageName/about',      Cat::POST],   // não é post
    ['https://www.facebook.com/PageName/about',      Cat::VIDEO],  // não é vídeo
    ['https://www.facebook.com/watch/',              Cat::VIDEO],  // sem v=ID
    ['https://fb.watch/',                            Cat::VIDEO],  // sem code
]);

dataset('many-facebook-urls', [
    ["https://www.facebook.com/Rapadura-Tech-2292492210775710/", 'Rapadura-Tech-2292492210775710'],
    ["https://web.facebook.com/Renova-M%C3%ADdia-101416801556176/", 'Renova-M%C3%ADdia-101416801556176'],
    ["https://www.facebook.com/Rádio-Visão-FM-879-464423113943744/", 'Rádio-Visão-FM-879-464423113943744'],
    ["https://pt-br.facebook.com/groups/153046561966979/", '153046561966979'],
    ["https://mobile.facebook.com/people/Gilmar-Marques/100009367677509", '100009367677509'],
    ["https://web.facebook.com/Review-de-Produtos-2209792269098964/", 'Review-de-Produtos-2209792269098964'],
    ["https://web.facebook.com/Revista-do-Correio-428890500534152/", 'Revista-do-Correio-428890500534152'],
    ["https://web.facebook.com/Radio-Caldas-Fm-104244826393441/", 'Radio-Caldas-Fm-104244826393441'],
    ["https://web.facebook.com/R%C3%A1dio-Comunit%C3%A1ria-Cinc%C3%A3o-FM-464159240346580/", 'R%C3%A1dio-Comunit%C3%A1ria-Cinc%C3%A3o-FM-464159240346580'],
    ["https://www.facebook.com/Rádio-A-Voz-do-Sertão332866466783356/", 'Rádio-A-Voz-do-Sertão332866466783356'],
    ["https://web.facebook.com/profile.php", 'profile.php'],
    ["https://www.facebook.com/Rádio-serra-verde-fm-1049-103574791586651/", 'Rádio-serra-verde-fm-1049-103574791586651'],
    ["https://www.facebook.com/Rádio-senador-FM-109085770570422/", 'Rádio-senador-FM-109085770570422'],
    ["https://www.facebook.com/people/JM-Santa-Cruz/100015103087038/", '100015103087038'],
    ["https://www.facebook.com/Radio-Rio-Espera-FM-987-709498765870098/", 'Radio-Rio-Espera-FM-987-709498765870098'],
    ["https://web.facebook.com/Radio-HB-FM-1049-135009464010205?_rdc=1&_rdr", 'Radio-HB-FM-1049-135009464010205'],
    ["https://www.facebook.com/people/Pop-Brito/100010377968387/", '100010377968387'],
    ["https://web.facebook.com/Radio-Juazeiro-AM-1190-Khz-239549639455945/", 'Radio-Juazeiro-AM-1190-Khz-239549639455945'],
    ["https://www.facebook.com/Paula-Freitas-FM-344904045595234/", 'Paula-Freitas-FM-344904045595234'],
    ["https://web.facebook.com/Lara-Fm-393253520749446/", 'Lara-Fm-393253520749446'],
    ["https://web.facebook.com/pages/Radio-Lunardelli-FM/11442855010880", '11442855010880'],
    ["https://www.facebook.com/Rádio-Oasis-FM-877-426445087445859/", 'Rádio-Oasis-FM-877-426445087445859'],
    ["https://web.facebook.com/R%C3%A1dio-Mais-Digital-322238374482581", 'R%C3%A1dio-Mais-Digital-322238374482581'],
    ["https://m.facebook.com/pages/O-Guia-Financeiro/", 'O-Guia-Financeiro'],
    ["https://www.facebook.com/Para%C3%ADba-Atual-239180756689626/", 'Para%C3%ADba-Atual-239180756689626'],
    ["https://www.facebook.com/Para%C3%ADba-Feminina-1102995589911144/", 'Para%C3%ADba-Feminina-1102995589911144'],
    ["https://www.facebook.com/Pier-Magazine-595118540900238/", 'Pier-Magazine-595118540900238'],
    ["https://www.facebook.com/Piraju%C3%AD-Radio-Clube-518163874913604/", 'Piraju%C3%AD-Radio-Clube-518163874913604'],
    ["https://web.facebook.com/Tribuna10-102146874772886/?_rdc=1&_rdr", 'Tribuna10-102146874772886'],
    ["https://www.facebook.com/InvestNordeste/about/", 'InvestNordeste'],
    ["https://www.facebook.com/Portal-das-Manas-116414553525328/", 'Portal-das-Manas-116414553525328'],
    ["https://www.facebook.com/R%C3%A1dio-Princesa-FM-1035-104998581219484/", 'R%C3%A1dio-Princesa-FM-1035-104998581219484'],
    ["https://www.facebook.com/gaming/pkamikat", 'pkamikat'],
    ["https://www.facebook.com/A-Gazeta-do-Acre-208579685881525/", 'A-Gazeta-do-Acre-208579685881525'],
    ["https://www.facebook.com/ABAAS-2449568795264701", 'ABAAS-2449568795264701'],
    ["https://www.facebook.com/Adamantina-Net-690947984682278", 'Adamantina-Net-690947984682278'],
    ["https://www.facebook.com/groups/ANDAnews/", 'ANDAnews'],
    ["https://www.facebook.com/Atalaia-FM-1049-321450894714312/", 'Atalaia-FM-1049-321450894714312'],
    ["https://www.facebook.com/belezinhacomvc-1504942329727056/", 'belezinhacomvc-1504942329727056'],
    ["https://www.facebook.com/Cassilandia-Urgente-2042059232747218/", 'Cassilandia-Urgente-2042059232747218'],
    ["https://www.facebook.com/Almir-macedo-blog-113186617044796/", 'Almir-macedo-blog-113186617044796'],
    ["https://www.facebook.com/Blog-Futebol-Cultura-e-Geografia-1522229501229741/", 'Blog-Futebol-Cultura-e-Geografia-1522229501229741'],
    ["https://www.facebook.com/Blog-T%C3%A2nia-M%C3%BCller-1727534527496965/", 'Blog-T%C3%A2nia-M%C3%BCller-1727534527496965'],
    ["https://www.facebook.com/Blog-das-Locadoras-de-Ve%C3%ADculos-289534768390092/", 'Blog-das-Locadoras-de-Ve%C3%ADculos-289534768390092'],
    ["https://www.facebook.com/Blog-do-Fredson-170130229730634/", 'Blog-do-Fredson-170130229730634'],
    ["https://www.facebook.com/Blog-Henrique-Barbosa-112302063592706/", 'Blog-Henrique-Barbosa-112302063592706'],
    ["https://www.facebook.com/BLOG-POR-SIMAS-195977007169609/", 'BLOG-POR-SIMAS-195977007169609'],
    ["https://www.facebook.com/R%C3%A1dio-Cidade-Bela-FM-415311565214258/", 'R%C3%A1dio-Cidade-Bela-FM-415311565214258'],
    ["https://www.facebook.com/Brasil-Soberano-e-Livre-164872757030205/", 'Brasil-Soberano-e-Livre-164872757030205'],
    ["https://www.facebook.com/Quadro-Feminino", 'Quadro-Feminino'],
    ["https://www.facebook.com/Blog-Camocim-Imparcial-603555149658685", 'Blog-Camocim-Imparcial-603555149658685'],
    ["https://www.facebook.com/Cariri-Ativo-112153933875410/", 'Cariri-Ativo-112153933875410'],
    ["https://www.facebook.com/R%C3%A1dio-Cidade-Fm-931-390073081789694/", 'R%C3%A1dio-Cidade-Fm-931-390073081789694'],
    ["https://www.facebook.com/Piraju%C3%AD-Radio-Clube-518163874913604/", 'Piraju%C3%AD-Radio-Clube-518163874913604'],
    ["https://www.facebook.com/R%C3%A1dio-Clube-750-AM-117620645014196/", 'R%C3%A1dio-Clube-750-AM-117620645014196'],
    ["https://www.facebook.com/login/conexaoboasnoticias", 'conexaoboasnoticias'],
    ["https://www.facebook.com/Converg%C3%AAncia-Digital-112284628857729/", 'Converg%C3%AAncia-Digital-112284628857729'],
    ["https://pt-br.facebook.com/correio24horas%2F", 'correio24horas%2F'],
    ["https://www.facebook.com/Corumb%C3%A1-Pires-Do-Rio-734098520070490/", 'Corumb%C3%A1-Pires-Do-Rio-734098520070490'],
    ["https://www.facebook.com/Cosmetics-Toiletries-Brasil-380514552316210/", 'Cosmetics-Toiletries-Brasil-380514552316210'],
    ["https://www.facebook.com/Cozinha-da-Jan-112207870535655/", 'Cozinha-da-Jan-112207870535655'],
    ["https://pt-br.facebook.com/Df-Manchetes-162738151012876/", 'Df-Manchetes-162738151012876'],
    ["https://www.facebook.com/Jornal-Di%C3%A1rio-de-Cuiab%C3%A1-580779489064442/", 'Jornal-Di%C3%A1rio-de-Cuiab%C3%A1-580779489064442'],
    ["https://www.facebook.com/Difundir-182759678419629/", 'Difundir-182759678419629'],
    ["https://www.facebook.com/Esperan%C3%A7a-FM-231600826920012", 'Esperan%C3%A7a-FM-231600826920012'],
    ["https://www.facebook.com/FOLHA-DE-PONTE-NOVA-199872646740004/", 'FOLHA-DE-PONTE-NOVA-199872646740004'],
    ["https://www.facebook.com/Fast-Company-Brasil-102161058375474", 'Fast-Company-Brasil-102161058375474'],
    ["https://www.facebook.com/Info-Investimento-188482012670080", 'Info-Investimento-188482012670080'],
    ["https://www.facebook.com/pages/Ingles-no-Supermercado/", 'Ingles-no-Supermercado'],
    ["https://www.facebook.com/Folhasanjoense-2063834583852565/", 'Folhasanjoense-2063834583852565'],
    ["https://www.facebook.com/Gazeta-Minas-884417981623652", 'Gazeta-Minas-884417981623652'],
    ["https://www.facebook.com/Jornal-do-Golfe-Brasil-235118343201440/", 'Jornal-do-Golfe-Brasil-235118343201440'],
    ["https://m.facebook.com/pg/Jornalfogocruzado/", 'Jornalfogocruzado'],
    ["https://www.facebook.com/Mundodasmarcas-467041903352382/", 'Mundodasmarcas-467041903352382'],
    ["https://web.facebook.com/napautaonline/about/?ref=page_internal", 'napautaonline'],
    ["https://web.facebook.com/NewsCuiaba-172009749533058/?_rdc=1&_rdr", 'NewsCuiaba-172009749533058'],
    ["https://web.facebook.com/R%C3%A1dio-Canoa-Grande-FM-907-309386819494298?_rdc=1&_rdr", 'R%C3%A1dio-Canoa-Grande-FM-907-309386819494298'],
    ["https://web.facebook.com/Reino-Kawaii-105209198542040/?_rdc=1&_rdr", 'Reino-Kawaii-105209198542040'],
    ["https://www.facebook.com/R%C3%A1dio-Menina-FM-434350903361005", 'R%C3%A1dio-Menina-FM-434350903361005'],
    ["https://www.facebook.com/MidiaFestcom-534565576687035/", 'MidiaFestcom-534565576687035'],
    ["https://m.facebook.com/pages/category/Organization/Mundo-Positivo-270615286364815/?locale2=pt_BR", 'Mundo-Positivo-270615286364815'],
    ["https://www.facebook.com/Minas1-118482035162309/", 'Minas1-118482035162309'],
    ["https://www.facebook.com/pages/Moda-e-Imagem/365613966786175", '365613966786175'],
    ["https://www.facebook.com/tudozikcom-690252844338570", 'tudozikcom-690252844338570'],
    ["https://www.facebook.com/Piaui24hs-355162251265816/", 'Piaui24hs-355162251265816'],
    ["https://www.facebook.com/Nota-Alta-ESPM-308219805931205/", 'Nota-Alta-ESPM-308219805931205'],
    ["https://www.facebook.com/groups/aprendendosobreprodutinhos/", 'aprendendosobreprodutinhos'],
    ["https://www.facebook.com/Paulo-Roberto-da-Radio-146259889201086/", 'Paulo-Roberto-da-Radio-146259889201086'],
    ["https://www.facebook.com/R%C3%A1dio-Rep%C3%BAblica-1380-Khz-Morro-Agudo-100824490065898/", 'R%C3%A1dio-Rep%C3%BAblica-1380-Khz-Morro-Agudo-100824490065898'],
    ["https://web.facebook.com/Radio-Arco-Iris-1660166144097703", 'Radio-Arco-Iris-1660166144097703'],
    ["https://web.facebook.com/Jornal-Metropolitano-Rio-256217641738558/", 'Jornal-Metropolitano-Rio-256217641738558'],
    ["https://www.facebook.com/Tudo-Dos-Famosos-101728215019657", 'Tudo-Dos-Famosos-101728215019657'],
    ["https://www.facebook.com/selecoes/instagram", 'instagram'],
    ["https://www.facebook.com/groups/portalnacional/", 'portalnacional'],
    ["https://www.facebook.com/Revista-EXCLUSIVA-113696675398498/", 'Revista-EXCLUSIVA-113696675398498'],
    ["https://web.facebook.com/Clube-Cidade-FM-1065-331078396983453/", 'Clube-Cidade-FM-1065-331078396983453'],
    ["https://web.facebook.com/Mercado-Lance-103347361619080/", 'Mercado-Lance-103347361619080'],
    ["https://www.facebook.com/Funda%C3%A7%C3%A3o-Ecoamaz%C3%B4nia-1166332020139207/", 'Funda%C3%A7%C3%A3o-Ecoamaz%C3%B4nia-1166332020139207'],
    ["https://www.facebook.com/Luiza-Carvalho-Cardoso-1985062648206772/", 'Luiza-Carvalho-Cardoso-1985062648206772'],
    ["https://www.facebook.com/pages/Jornal%20Monitor%20Mercantil/362368017692194/", '362368017692194'],
    ["https://www.facebook.com/NewVoice-101179635175629", 'NewVoice-101179635175629'],
    ["https://www.facebook.com/Tuim-Blog-Nota-e-Anota-123397729060385", 'Tuim-Blog-Nota-e-Anota-123397729060385'],
    ["https://www.facebook.com/O-Jornal-da-Regi%C3%A3o-416481705109309", 'O-Jornal-da-Regi%C3%A3o-416481705109309'],
    ["https://www.facebook.com/Procel-Info-189940331030640", 'Procel-Info-189940331030640'],
    ["https://www.facebook.com/RADIO-inova%C3%A7ao-FM-2020756491532659", 'RADIO-inova%C3%A7ao-FM-2020756491532659'],
    ["https://www.facebook.com/Revolti-Play-100308601695075", 'Revolti-Play-100308601695075'],
    ["https://www.facebook.com/S%C3%A3o-Paulo-Na-Web-232806284153128", 'S%C3%A3o-Paulo-Na-Web-232806284153128'],
    ["https://www.facebook.com/groups/portalnacional/", 'portalnacional'],
    ["https://www.facebook.com/pages/Sistema%20Ocepar/107602682658357/", '107602682658357'],
    ["https://www.facebook.com/terraviva_tvv-109419477126835/", 'terraviva_tvv-109419477126835'],
    ["https://www.facebook.com/Valor-PRO-103220501226811", 'Valor-PRO-103220501226811'],
    ["https://www.facebook.com/A-voz-dos-munic%C3%ADpios-787286114984921", 'A-voz-dos-munic%C3%ADpios-787286114984921'],
    ["https://www.facebook.com/ABEG%C3%81S-148612331924025/", 'ABEG%C3%81S-148612331924025'],
    ["https://www.facebook.com/Acesse-Not%C3%ADcias-611468469015513/?fref=ts", 'Acesse-Not%C3%ADcias-611468469015513'],
    ["https://www.facebook.com/AgroMogiana-1649371848661720/", 'AgroMogiana-1649371848661720'],
    ["https://www.facebook.com/groups/889643667801967", '889643667801967'],
    ["https://www.facebook.com/Blog-Caruaru-em-Pauta-342184219889589", 'Blog-Caruaru-em-Pauta-342184219889589'],
    ["https://www.facebook.com/Blog-do-Z%C3%A9-Carlos-Borges-271930693697025", 'Blog-do-Z%C3%A9-Carlos-Borges-271930693697025'],
    ["https://www.facebook.com/Carlos-Lima-Jornal-Online-396186957136964/?ref=page_internal", 'Carlos-Lima-Jornal-Online-396186957136964'],
    ["https://www.facebook.com/PortalDestakNews/about/?ref=page_internal", 'PortalDestakNews'],
    ["https://www.facebook.com/Dia-da-Not%C3%ADcia-104210671260020", 'Dia-da-Not%C3%ADcia-104210671260020'],
    ["https://www.facebook.com/Em-Campos-do-Jord%C3%A3o-158573430879441", 'Em-Campos-do-Jord%C3%A3o-158573430879441'],
    ["https://www.facebook.com/Magno-Martins-Engenharia-326399670722112/", 'Magno-Martins-Engenharia-326399670722112'],
    ["https://www.facebook.com/M%C3%A1quinas-Equipamentos-102643904740769/", 'M%C3%A1quinas-Equipamentos-102643904740769'],
    ["https://www.facebook.com/Minas1-118482035162309/", 'Minas1-118482035162309'],
    ["https://www.facebook.com/Maranh%C3%A3o-MA-2415515931856519/?eid=ARD4e81kEH_FKybdUu5TzigX3M-zAjYMalw37KkvXeRnNhHpsPW_RdmhQf-K00MCXz_MY_svyaVDpjiG", 'Maranh%C3%A3o-MA-2415515931856519'],
    ["https://www.facebook.com/Sert%C3%A3o-Central-365459527401778", 'Sert%C3%A3o-Central-365459527401778'],
    ["https://www.facebook.com/groups/307932635974424", '307932635974424'],
    ["https://www.facebook.com/Surubim-Not%C3%ADcias-534659486669306", 'Surubim-Not%C3%ADcias-534659486669306'],
    ["https://www.facebook.com/TAVI-Latam-802855023441952/?modal=admin_todo_tour", 'TAVI-Latam-802855023441952'],
    ["https://www.facebook.com/TeclandoWebcombr-505678006254118/", 'TeclandoWebcombr-505678006254118'],
    ["https://www.facebook.com/Tribo-Gamer-292478180794143", 'Tribo-Gamer-292478180794143'],
    ["https://www.facebook.com/Tudo-OK-Not%C3%ADcias-249945488760480/", 'Tudo-OK-Not%C3%ADcias-249945488760480'],
    ["https://www.facebook.com/Tv-Vertentes-1575936005826217/", 'Tv-Vertentes-1575936005826217'],
    ["https://www.facebook.com/PB-24-horas-Para%C3%ADba-112303307137610/", 'PB-24-horas-Para%C3%ADba-112303307137610'],
    ["https://www.facebook.com/ASAS-Assessment-of-SpondyloArthritis-international-Society-104590927632503/", 'ASAS-Assessment-of-SpondyloArthritis-international-Society-104590927632503'],
    ["https://www.facebook.com/Cidad%C3%A3o-Votorantinense-SA-109870008047964", 'Cidad%C3%A3o-Votorantinense-SA-109870008047964'],
    ["https://www.facebook.com/pages/Correio-do-Interior/273582366438235", '273582366438235'],
    ["https://www.facebook.com/Jornal-de-Bairro-em-Bairro-443103309099191/", 'Jornal-de-Bairro-em-Bairro-443103309099191'],
    ["https://www.facebook.com/Jornal-Folha-de-Votorantim-385615991959202/", 'Jornal-Folha-de-Votorantim-385615991959202'],
    ["https://www.facebook.com/NewVoice-101179635175629", 'NewVoice-101179635175629'],
    ["https://www.facebook.com/Converg%C3%AAncia-Digital-112284628857729/", 'Converg%C3%AAncia-Digital-112284628857729'],
    ["https://www.facebook.com/Ademi-Rio-180189805418768/", 'Ademi-Rio-180189805418768'],
    ["https://www.facebook.com/AMZ-Noticias-168490407148188", 'AMZ-Noticias-168490407148188'],
    ["https://www.facebook.com/blogdehollywoodoficial/about/?ref=page_internal", 'blogdehollywoodoficial'],
    ["https://www.facebook.com/BLOG-De-Jamildo-744590685678022", 'BLOG-De-Jamildo-744590685678022'],
    ["https://www.facebook.com/Camapu%C3%A3-News-511582495618072/", 'Camapu%C3%A3-News-511582495618072'],
    ["https://www.facebook.com/PIB-Presen%C3%A7a-Internacional-do-Brasil-179165825456479", 'PIB-Presen%C3%A7a-Internacional-do-Brasil-179165825456479'],
    ["https://www.facebook.com/Pinzon.noticias/about/?ref=page_internal", 'Pinzon.noticias'],
    ["https://www.facebook.com/Pol%C3%ADtica-Paraibana-106723214701508/", 'Pol%C3%ADtica-Paraibana-106723214701508'],
    ["https://www.facebook.com/groups/ouvintesdauspfm", 'ouvintesdauspfm'],
    ["https://www.facebook.com/Rapid-TV-News-188875487818380/", 'Rapid-TV-News-188875487818380'],
    ["https://www.facebook.com/pages/category/App-Page/4gnewspt/posts/", '4gnewspt'],
]);


describe('Facebook', function () {
    //
    // TESTES DE EXTRAÇÃO
    //

    test('extracts PROFILE id from vanity URLs', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe($expected);
    })->with('fb-profile-vanity');

    test('extracts PROFILE id from profile.php numeric', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe($expected);
    })->with('fb-profile-numeric');

    test('extracts POST id from many formats', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::POST))->toBe($expected);
    })->with('fb-posts');

    test('extracts VIDEO id from many formats (videos/, video.php, watch, fb.watch, reel)', function (string $url, string $expected) {
        expect($this->extractor->extractId($url, Cat::VIDEO))->toBe($expected);
    })->with('fb-videos');

    //
    // NEGATIVOS — deve lançar \InvalidArgumentException quando o tipo não bate
    //

    test('throws when URL does not match requested category', function (string $url, Cat $type) {
        $this->expectException(\InvalidArgumentException::class);
        $this->extractor->extractId($url, $type);
    })->with('fb-negatives');

    //
    // Mantém seus testes originais como fumaça rápida
    //

    it('extracts numeric profile ID from profile.php URL (smoke)', function () {
        $url = 'https://facebook.com/profile.php?id=123456789';
        expect($this->extractor->extractId($url, Cat::PROFILE))->toBe('123456789');
    });

    it('extracts post ID from /posts/ (smoke)', function () {
        $url = 'https://m.facebook.com/ExamplePage/posts/987654321';
        expect($this->extractor->extractId($url, Cat::POST))->toBe('987654321');
    });

    it('extracts video ID from fb.watch short link (smoke)', function () {
        $url = 'https://fb.watch/abcDEF123/';
        expect($this->extractor->extractId($url, Cat::VIDEO))->toBe('abcDEF123');
    });

    test('many facebook URLs', function (string $url, string $expected) {
        $extractor = new SocialMediaUrlIdExtractor();
        expect($extractor->extract($url)->id)->toBe($expected);
    })->with('many-facebook-urls');
});