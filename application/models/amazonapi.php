<?php
/**
 * Created by PhpStorm.
 * User: Nikhil
 * Date: 02-11-2015
 * Time: 15:17
 */
class Amazonapi extends CI_Model
{

    const AWSAccessKeyId = "AKIAJVQRJSENON6P5UOA";
    const AWSSecretKey = "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";

    /**
     * @param string $category --category can be books, electronics etc.
     * @return SimpleXMLElement
     */
    public function getProducts($category="",$responseGroup="")
    {
        $params=array();
        $browseNodeId=$this->getBrowseNodeId($category);
        $params['Operation']='BrowseNodeLookup';
        $params['BrowseNodeId']=$browseNodeId;
        $params['ResponseGroup']=$responseGroup;
        $response=$this->getData($params);
        //print_r($response);
        $response->registerXPathNamespace("Element","http://webservices.amazon.com/AWSECommerceService/2011-08-01");
        if($response->xpath("//Element:Error")!=false)
        {
             return "Something Went Wrong!! We are sorry for inconvenience :(";
        }
        else
        {

            $response=$response->xpath("//Element:ASIN");
            $response=array_unique($response);
            $title=array();

            for($i=0;$i<=count($response)-1;$i++)
            {
                  $title[$i]=$this->getTitlePriceImageBasedOnProductId($response[$i]);
            }

            return $title;
        }

    }

    public function getTitlePriceImageBasedOnProductId($productId)
    {
        $params=array("Operation"=>"ItemLookup","ItemId"=>$productId,"ResponseGroup"=>"Medium");
        $response=$this->getData($params);
        $response->registerXPathNamespace("Element","http://webservices.amazon.com/AWSECommerceService/2011-08-01");
        if($response->xpath("//Element:Error")!=false)
        {
            return "Something Went Wrong!! We are sorry for inconvenience :(";
        }
        else
        {
            $ASIN=$productId;
            $title=array_unique($response->xpath("//Element:Title"));
            $price=array_unique($response->xpath("//Element:FormattedPrice"));
            $mediumImages=array_unique($response->xpath("//Element:MediumImage"));
            $response=array('ASIN'=>$ASIN,
                'Title'=>$title,
                'Price'=>$price,
                'Image'=>$mediumImages
                );
            return $response;
        }

    }


    public function getProductDetails($productId)
    {
        $params=array("Operation"=>"ItemLookup","ItemId"=>$productId,"ResponseGroup"=>"Medium");
        $response=$this->getData($params);
        $response->registerXPathNamespace("Element","http://webservices.amazon.com/AWSECommerceService/2011-08-01");
        if($response->xpath("//Element:Error")!=false)
        {
            return "Something Went Wrong!! We are sorry for inconvenience :(";
        }
        else
        {
            $ASIN=$productId;
            $thumnailImages=array_unique($response->xpath("//Element:ThumbnailImage"));
            $largeImages=array_unique($response->xpath("//Element:LargeImage"));
            $features=array_unique($response->xpath("//Element:Feature"));
            $title=array_unique($response->xpath("//Element:Title"));
            $content=array_unique($response->xpath("//Element:Content"));
            $price=array_unique($response->xpath("//Element:FormattedPrice"));
            $color=array_unique($response->xpath("//Element:Color"));
            $brand=array_unique($response->xpath("//Element:Brand"));
            $packageDimensions=array_unique($response->xpath("//Element:PackageDimensions"));
            $manufacturer=array_unique($response->xpath("//Element:Manufacturer"));
            $publisher=array_unique($response->xpath("//Element:Publisher"));
            $response=array('ASIN'=>$ASIN,
                            'Title'=>$title,
                            'Price'=>$price,
                            'Manufacturer'=>$manufacturer,
                            'Publisher'=>$publisher,
                            'Brand'=>$brand,
                            'Content'=>$content,
                            'Color'=>$color,
                            'LargeImages'=>$largeImages,
                            'ThumbnailImages'=>$thumnailImages,
                            'Dimensions'=>$packageDimensions,
                            'Features'=>$features);
            return $response;
        }

    }

    /**
     * returns the parsed XML as a SimpleXMLElement object based on the signed url and requested operation
     * @return SimpleXMLElement
     */
    public function getData($params){
        $signedurl=$this->aws_signed_request($params);
        $response=$this->fetchData($signedurl);
       /* if(isset($response->Error))
        {
            return "Something Went Wrong!! We are sorry for inconvinience :(";
        }
        else{
            $parsedXML=simplexml_load_string($response);
            return $parsedXML;
        }*/
        $parsedXML=simplexml_load_string($response);
        return $parsedXML;


    }

    /**
     * returns browse node ID of the category which in turn is useful in finding new releases
     * @param $category
     * @return int
     */
    private function getBrowseNodeId($category)
    {
        switch ($category) {

            case "Baby":
                return 1571274031;

            case "Books":
                return 976389031;

            case "DVD":
                return 976416031;

            case "Electronics":
                return 976419031;

            case "HomeGarden":
                return 976442031;

            case "Jewelry":
                return 1951048031;

            case "PCHardware":
                return 976392031;

            case "Toys":
                return 1350380031;

            case "Watches":
                return 1350387031;

            default:
                return 976419031;
        }
    }


    /**
     *
     * used to fetch data from amazon api using curl
     * @param $url
     * @return mixed
     */
    public function fetchData($url)
    {
        $this->curl->create($url);
        $this->curl->option('returntransfer',1);//to not display directly but to return the data
        $this->curl->option('HEADER',false);
        return $this->curl->execute();
    }

    /**
     * used to create signed url which in turn is used to fetch data from amazon api
     * @param $params
     * @return string
     */
    public function aws_signed_request($params)
    {
        $region="in";
        $public_key=self::AWSAccessKeyId;
        $private_key=self::AWSSecretKey;
        $associate_tag=NULL;
        $version='2011-08-01';


        $method = 'GET';
        $host = 'webservices.amazon.'.$region;
        $uri = '/onca/xml';


        $params['Service'] = 'AWSECommerceService';
        $params['AWSAccessKeyId'] = $public_key;

        $params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');

        $params['Version'] = $version;
        if ($associate_tag !== NULL) {
            $params['AssociateTag'] = $associate_tag;
        }
        else
            $params['AssociateTag'] = 'NULL';


        ksort($params);

        $canonicalized_query = array();
        foreach ($params as $param=>$value)
        {
            $param = str_replace('%7E', '~', rawurlencode($param));
            $value = str_replace('%7E', '~', rawurlencode($value));
            $canonicalized_query[] = $param.'='.$value;
        }
        $canonicalized_query = implode('&', $canonicalized_query);


        $string_to_sign = $method."\n".$host."\n".$uri."\n".$canonicalized_query;


        $signature = base64_encode(hash_hmac('sha256', $string_to_sign, $private_key, TRUE));


        $signature = str_replace('%7E', '~', rawurlencode($signature));


        $request = 'http://'.$host.$uri.'?'.$canonicalized_query.'&Signature='.$signature;

        return $request;

    }

}