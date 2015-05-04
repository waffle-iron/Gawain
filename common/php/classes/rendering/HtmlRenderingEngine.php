<?php

require_once(__DIR__ . '/../../constants/global_defines.php');
require_once(PHP_ABSTRACTS_DIR . 'rendering/RenderingEngine.php');



class HtmlRenderingEngine extends RenderingEngine {

	public function setTemplate($str_Template) {

		$arr_Template = array();

		if ($this->dataType == 'entity') {

			// If the current target is an entity, retrieves data from entity tables
			// First, retrieve data regarding all fields for the given Template

			$str_Query = '
				select
					renderingTypeBeforeAllSnippet,
					renderingTypeAfterAllSnippet,
					renderingTypeBeforeEachRecordSnippet,
					renderingTypeBetweenEachRecordSnippet,
					renderingTypeAfterEachRecordSnippet
				from rendering_types
				where renderingTypeCode = ?
			';



			$obj_Resultset = $this->dbHandler->executePrepared($str_Query,
				array(
					array($str_Template =>  's')
				)
			);

			$arr_Template['common'] = $obj_Resultset[0];


			// Then, the info related to single fields
			$str_Query = '
				select
					link.columnName,
					link.fieldLabel,
					link.fieldTooltip,
					link.fieldOrderingIndex,
					link.fieldGroupingLevel,
					link.fieldGroupingFunction,
					render.elementBaseTag,
					render.elementTemplate
				from entities_linked_rendering_elements link
					left join rendering_elements render
						on link.fieldRenderingElementCode = render.elementCode
				where link.entityCode = ?
				    and link.customerID = ?
					and link.renderingTypeCode = ?
				order by
					link.fieldOrderingIndex,
					link.fieldLabel
			';

			$obj_Resultset = $this->dbHandler->executePrepared($str_Query,
				array(
					array($this->dataTypeID =>  's'),
					array($this->domainID   =>  'i'),
					array($str_Template  =>  's')
				)
			);


			// Sets the template key as the column name
			foreach ($obj_Resultset as $arr_Resultrow) {
				$str_ColumnName = $arr_Resultrow['columnName'];
				unset($arr_Resultrow['columnName']);
				$arr_Template['fields'][$str_ColumnName] = $arr_Resultrow;
			}

			$this->template = $arr_Template;

		}

	}



	public function render() {

	}
}